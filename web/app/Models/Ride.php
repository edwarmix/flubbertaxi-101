<?php

namespace App\Models;

use App\Notifications\NewRide;
use App\Notifications\RideStatusChanged;
use App\Repositories\DriverRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * Class Ride
 * @package App\Models
 * @version July 12, 2022, 12:01 pm UTC
 *
 * @property string $mid
 * @property integer $user_id
 * @property integer $driver_id
 * @property string $boarding_location
 * @property string $boarding_location_data
 * @property boolean $save_boarding_location_for_next_ride
 * @property string $destination_location_data
 * @property number $distance
 * @property number $driver_value
 * @property number $app_value
 * @property number $total_value
 * @property string $customer_observation
 * @property integer $offline_payment_method_id
 * @property string $payment_gateway
 * @property string $gateway_id
 * @property string $payment_status
 * @property string|\Carbon\Carbon $payment_status_date
 * @property string $ride_status
 * @property string|\Carbon\Carbon $ride_status_date
 * @property string $status_observation
 */
class Ride extends Model
{

    use HasFactory;

    public $table = 'rides';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    public static function boot()
    {
        parent::boot();

        static::created(function (Ride $model) {
            if (setting('enable_firebase')) {
                if ($model->ride_status != 'waiting') {
                    \Illuminate\Support\Facades\Notification::sendNow([$model->driver()->with('user')->first()->user], new NewRide($model));
                }
            }
        });

        static::updated(function (Ride $model) {
            //TODO: notify the customer or the driver acconding to who made the change and the status
            if (setting('enable_firebase')) {
                if ($model->ride_status != $model->getOriginal('ride_status')) {
                    $isCustomer = auth()->user()->id == $model->user_id;

                    if ($isCustomer) {
                        //notify driver
                        if ($model->getOriginal('ride_status') == 'waiting' && $model->ride_status != 'cancelled') {
                            \Illuminate\Support\Facades\Notification::sendNow([$model->driver()->with('user')->first()->user], new NewRide($model));
                        } else {
                            \Illuminate\Support\Facades\Notification::sendNow([$model->driver()->with('user')->first()->user], new RideStatusChanged($model));
                        }
                    } else {
                        //notify customer
                        \Illuminate\Support\Facades\Notification::sendNow([$model->user()->first()], new RideStatusChanged($model));
                    }
                }
            }
        });
    }

    public $fillable = [
        'user_id',
        'driver_id',
        'vehicle_type_id',
        'boarding_location',
        'boarding_location_data',
        'save_boarding_location_for_next_ride',
        'destination_location_data',
        'distance',
        'driver_value',
        'app_value',
        'total_value',
        'customer_observation',
        'offline_payment_method_id',
        'payment_gateway',
        'gateway_order_id',
        'gateway_id',
        'payment_status',
        'payment_status_date',
        'ride_status',
        'ride_status_date',
        'assigned_drivers',
        'driver_assigned_date',
        'status_observation'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'driver_id' => 'integer',
        'boarding_location' => 'string',
        'boarding_location_data' => 'string',
        'save_boarding_location_for_next_ride' => 'boolean',
        'destination_location_data' => 'string',
        'distance' => 'decimal:3',
        'driver_value' => 'decimal:2',
        'app_value' => 'decimal:2',
        'total_value' => 'decimal:2',
        'customer_observation' => 'string',
        'offline_payment_method_id' => 'integer',
        'payment_gateway' => 'string',
        'gateway_id' => 'string',
        'payment_status' => 'string',
        'payment_status_date' => 'datetime',
        'ride_status' => 'string',
        'ride_status_date' => 'datetime',
        'status_observation' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'user_id' => 'required|integer',
        'driver_id' => 'required|integer',
        'distance' => 'required|numeric',
        'driver_value' => 'required|numeric',
        'app_value' => 'required|numeric',
        'customer_observation' => 'nullable|string',
        'payment_status' => 'required|string',
        'ride_status' => 'required|string',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'user',
        'driver',
        'vehicle_type',
    ];

    public static function updateNextDriver(Ride $ride, DriverRepository $driverRepository)
    {
        if ($ride->ride_status == 'pending' && isset($ride->driver_assigned_date) && Carbon::now()->diffInSeconds(new Carbon($ride->driver_assigned_date)) > 30) {
            if(env('APP_DEMO',false)){
                //accept the order for demo purposes only
                $ride->ride_status = 'accepted';
                $ride->ride_status_date = Carbon::now();
                $ride->save();
                return;
            }
            $boardingAddress = json_decode($ride->boarding_location_data);
            $assignedDrivers = json_decode($ride->assigned_drivers);
            $nextDriver = $driverRepository->getDriversNearOf($boardingAddress->geometry->location->lat, $boardingAddress->geometry->location->lng, 10, true, false)
                ->whereNotIn('drivers.id', $assignedDrivers)
                ->where('drivers.vehicle_type_id', $ride->vehicle_type_id)
                ->where('drivers.active', true)
                ->groupBy('drivers.id')
                ->doesntHave('rides', 'and', function ($query) {
                    $query->where('rides.ride_status', 'accepted')
                        ->orWhere('rides.ride_status', 'in_progress');
                })
                ->first();
            if (isset($nextDriver)) {
                $assignedDrivers[] = $nextDriver->id;
                $ride->driver_id = $nextDriver->id;
                $ride->driver_assigned_date = Carbon::now();
            } else {
                $nextDriver = $driverRepository->getDriversNearOf($boardingAddress->geometry->location->lat, $boardingAddress->geometry->location->lng, 10, true, false)
                    ->where('drivers.vehicle_type_id', $ride->vehicle_type_id)
                    ->where('drivers.active', true)
                    ->groupBy('drivers.id')
                    ->doesntHave('rides', 'and', function ($query) {
                        $query->where('rides.ride_status', 'accepted')
                            ->orWhere('rides.ride_status', 'in_progress');
                    })
                    ->first();
                if (isset($nextDriver)) {
                    $ride->driver_id = $nextDriver->id;
                    $assignedDrivers = [$nextDriver->id];
                    $ride->driver_assigned_date = Carbon::now();
                }
            }
            $ride->assigned_drivers = $assignedDrivers;

            $ride->save();
        }
    }

    public function getUserAttribute()
    {
        return $this->user()->first();
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function getVehicleTypeAttribute()
    {
        return $this->vehicleType()->first();
    }

    public function getDriverAttribute()
    {
        return $this->driver()->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function offlinePaymentMethod()
    {
        return $this->belongsTo(OfflinePaymentMethod::class, 'offline_payment_method_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

/**
 * Class Driver
 * @package App\Models
 * @version July 7, 2022, 4:45 pm UTC
 *
 * @property boolean $active
 * @property string|\Carbon\Carbon $last_location_at
 * @property number $lat
 * @property number $lng
 * @property number $base_price
 * @property number $base_distance
 * @property number $additional_distance_pricing
 */
class Driver extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'drivers';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $dates = ['deleted_at'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Driver $model) {

            //generate slug if it's not generated yet
            if (empty($model->slug)) {
                $slug = Str::slug($model->user->name);
                $originalSlug = $slug;
                $c = 0;
                do {
                    $hasMarketSameSlug = self::where('slug', $slug)->exists();
                    if ($hasMarketSameSlug) {
                        $c++;
                        $slug = $originalSlug . "-" . $c;
                        $founded = true;
                    } else {
                        $founded = false;
                    }
                } while ($founded);
                $model->slug = $slug;
            }
        });
    }

    public $fillable = [
        'active',
        "user_id",
        'last_location_at',
        'lat',
        'lng',
        "slug",
        "vehicle_type_id",
        "brand",
        "model",
        "plate",
        "vehicle_document",
        "driver_license_url",
        "status_observation",
        "status",
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'active' => 'boolean',
        'last_location_at' => 'datetime',
        'lat' => 'decimal:8',
        'lng' => 'decimal:8',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'active' => 'required|boolean',
        'last_location_at' => 'nullable',
        'lat' => 'nullable|numeric',
        'lng' => 'nullable|numeric',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];

    protected $appends = [
        'link',
        'vehicle_type',
    ];

    public function getLinkAttribute()
    {
        return url("/{$this->slug}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicleType()
    {
        return $this->belongsTo(VehicleType::class);
    }

    public function getVehicleTypeAttribute()
    {
        return $this->vehicleType()->first();
    }

    public function getDriverLicenseUrlAttribute($value)
    {
        return $value != null ? url("/storage/{$value}") : null;
    }
    public function rides()
    {
        return $this->hasMany(Ride::class);
    }
}

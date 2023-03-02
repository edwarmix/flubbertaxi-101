<?php

namespace App\Http\Controllers\API\Driver;

use App\Repositories\DriverRepository;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDriverAPIRequest;
use App\Http\Requests\UpdateDriverAPIRequest;
use App\Models\Driver;
use App\Models\User;
use App\Repositories\RoleRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DriverAPIController extends Controller
{
    private $roleRepository;
    private $driverRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RoleRepository $roleRepository, DriverRepository $driverRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->driverRepository = $driverRepository;
    }


    /**
     * Display a listing of the Driver.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'lat' => 'required|between:-90,90',
            'lng' => 'required|between:-180,180'
        ]);
        try {
            $drivers = $this->driverRepository->getDriversNearOf($request->lat, $request->lng, 20, false, false)
            ->doesntHave('rides', 'and', function ($query) {
                $query->where('rides.ride_status', 'accepted')
                    ->orWhere('rides.ride_status', 'in_progress');
            })->get();
            $drivers->each(function ($driver) {
                $driver->user->setAppends([]);
            });
            return $this->sendResponse($drivers, __('Drivers founded'));
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(__('An error has occurred'), 500);
        }
    }


    public function updateRegister(UpdateDriverAPIRequest $request)
    {
        try {

            DB::beginTransaction();

            $inputsUser = $request->only([
                "name",
                "email",
                "phone",
                "firebase_token",
            ]);

            if(env('APP_DEMO') && $inputsUser['email'] == 'admin@admin.com'){
                return $this->sendError('This action is disabled in demo mode');
            }

            $inputsDriver = $request->only([
                "driver.brand",
                "driver.model",
                "driver.plate",
                "driver.vehicle_document",
                "driver.status_observation",
                "driver.vehicle_type_id"
            ])['driver'];

            $user = User::updateOrCreate(['api_token' => $request->api_token], $inputsUser + ['password' => bcrypt($request->password)]);
            try {
                if ($request->hasFile('driver_license')) {
                    $inputsDriver["driver_license_url"] = $request->file('driver_license')->storeAs('documents/driver_license', uniqid() . '.' . $request->file('driver_license')->getClientOriginalExtension(), 'public');
                }
            } catch (\Exception $e) {
                report($e);
                return $this->sendError(__("Error uploading driver's license"), 500);
            }
            Driver::updateOrCreate(['user_id' => $user->id], array_merge(['active' => false, 'status' => 'pending'], $inputsDriver));
            $user->makeVisible('api_token');

            DB::commit();
            return $this->sendResponse($user, 'Account updated successfully');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /*
     * Register a new user as driver
     */
    public function register(CreateDriverAPIRequest $request)
    {
        try {

            DB::beginTransaction();

            $inputsUser = $request->only([
                "name",
                "email",
                "password",
                "phone",
                "firebase_token",
            ]);
            $inputsDriver = $request->only([
                "driver.brand",
                "driver.model",
                "driver.plate",
                "driver.vehicle_document",
                "driver.status_observation",
                "driver.vehicle_type_id"
            ])['driver'];

            if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {
                $user = Auth::user()->makeVisible('api_token');
                if (!Auth::user()->hasRole('driver')) {
                    Auth::user()->assignRole('driver');
                }
                try {
                    $inputsDriver["driver_license_url"] = $request->file('driver_license')->storeAs('documents/driver_license', uniqid() . '.' . $request->file('driver_license')->getClientOriginalExtension(), 'public');
                } catch (\Exception $e) {
                    report($e);
                    return $this->sendError(__("Error uploading driver's license"), 500);
                }
                Driver::updateOrCreate(['user_id' => $user->id], ['active' => false, 'status' => 'pending'] + $inputsDriver);
                $user->api_token = Str::random(60);
                $user->makeVisible('api_token');
                $user->save();
                DB::commit();
                return $this->sendResponse($user, 'Account already created');
            }
            $request->validate([
                'email' => 'unique:users,email',
            ]);

            $user = User::create($inputsUser + ['api_token' => Str::random(60)]);
            if ($request->has('photo_url')) {
                try {
                    $customUuid = Str::random();
                    $upload = $this->uploadRepository->create(['uuid' => $customUuid]);
                    $upload->addMediaFromUrl($request->photo_url)
                        ->withCustomProperties(['uuid' => $customUuid])
                        ->toMediaCollection('avatar');
                    $cacheUpload = $this->uploadRepository->getByUuid($customUuid);
                    if (isset($cacheUpload)) {
                        $mediaItem = $cacheUpload->getMedia('avatar')->first();
                        $mediaItem->copy($user, 'avatar');
                    }
                } catch (\Exception $e) {
                    //
                }
            }
            try {
                $driver_license_url = $request->file('driver_license')->storeAs('documents/driver_license', uniqid() . '.' . $request->file('driver_license')->getClientOriginalExtension(), 'public');
            } catch (\Exception $e) {
                report($e);
                return $this->sendError(__("Error uploading driver's license"), 500);
            }
            Driver::updateOrCreate(['user_id' => $user->id], ['active' => false, 'status' => 'pending', 'driver_license_url' => $driver_license_url] + $inputsDriver);
            $user->makeVisible('api_token');

            $user->assignRole('driver');
            DB::commit();
            return $this->sendResponse($user, 'Account created successfully');
        } catch (\Exception $e) {
            report($e);
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /*
     * Login as driver
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        try {

            if (!Auth::attempt($credentials) || !Auth::user()->hasRole('driver')) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }

            $user = Auth::user()->makeVisible('api_token');
            auth()->user()->api_token = Str::random(60);
            auth()->user()->save();

            if ($user->driver->status != 'approved') {
                return $this->sendResponse($user, 'Status not regular', 202);
            }

            return $this->sendResponse($user, 'Login successfull');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
    /*
     * Check api token of driver
     */
    public function verifyLogin()
    {
        try {
            $user = Auth::user()->makeVisible('api_token');

            if (!$user->hasRole('driver')) {
                return $this->sendResponse($user, 'Status not regular', 401);
            } else if ($user->driver->status != 'approved') {
                return $this->sendResponse($user, 'Status not regular', 202);
            }

            return $this->sendResponse($user, 'Login verified');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function updateSettings(Request $request)
    {
        $settings = $request->validate([
            'base_distance' => 'numeric',
            'additional_distance_pricing' => 'numeric',
            'base_price' => 'numeric',
        ]);

        if (!setting('allow_custom_ride_values', true)) {
            return $this->sendError(__('Custom ride values not allowed'));
        }

        try {
            $user = User::find(Auth::user()->id);
            $user->driver->update($settings);
            return $this->sendResponse($user, 'Settings updated successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }


    public function getRideActive()
    {
        try {
            $active = Auth::user()->driver->active;

            return $this->sendResponse(['active' => __($active)], 'Ride status retrieved');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function updateRideActive(Request $request)
    {
        $data = $request->validate([
            'active' => 'required|boolean'
        ]);

        try {
            $user = Auth::user();

            $user->driver()->update(['active' => $data['active']]);
            $user->save();

            $active = Auth::user()->driver->active;

            return $this->sendResponse(['active' => __($active)], 'Ride status updated');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    public function updateLocation(Request $request)
    {
        $location = $request->validate([
            'lat' => 'required|between:-90,90',
            'lng' => 'required|between:-180,180'
        ]);
        try {
            $user = Auth::user();
            $user->driver()->update(array_merge([
                'last_location_at' => Carbon::now()
            ], $location));
            return $this->sendResponse(null, 'Location updated successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /*
     * Find the drivers near of pickup location
     */
    public function findNearBy(Request $request)
    {
        try {
            $pickupPlace = json_decode($request->input('boarding_address_data', '{}'), true);
            if (isset($pickupPlace['geometry']['location']['lat'])) {
                $lat = (float)$pickupPlace['geometry']['location']['lat'];
                $lng = (float)$pickupPlace['geometry']['location']['lng'];

                //search the most near drivers
                $drivers = $this->driverRepository->getDriversNearOf($lat, $lng)->where('vehicle_type_id', $request->vehicle_type_id);

                $driversFound = array();
                foreach ($drivers as $driver) {
                    $driversFound[$driver->id] = [
                        'id' => $driver->id,
                        'slug' => $driver->slug,
                        'name' => $driver->user->name,
                        'distance' => number_format($driver->distance, 1) . ' ' . setting('distance_unit', 'mi'),
                        'avatar' => $driver->user->media->first()->thumb ?? '',
                        'rides_count' => getTextForRideCount($driver->rides_count),
                    ];
                }
            } else {
                $driversFound = array();
            }

            return $this->sendResponse(array_values($driversFound), 'Drivers founded');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError($e->getMessage(), 500);
        }
    }
}

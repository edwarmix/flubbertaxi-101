<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\RideDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateRideRequest;
use App\Http\Requests\UpdateRideRequest;
use App\Models\Ride;
use App\Repositories\DriverRepository;
use App\Repositories\OfflinePaymentMethodRepository;
use App\Repositories\RideRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;

class RideController extends AppBaseController
{
    /** @var RideRepository $rideRepository*/
    private $rideRepository;
    /** @var UserRepository $userRepository*/
    private $userRepository;
    /** @var DriverRepository $driverRepository*/
    private $driverRepository;
    /** @var OfflinePaymentMethodRepository $offlinePaymentMethodRepository*/
    private $offlinePaymentMethodRepository;

    public function __construct(RideRepository $rideRepo, UserRepository $userRepo, DriverRepository $driverRepo, OfflinePaymentMethodRepository $offlinePaymentMethodRepo)
    {
        $this->rideRepository = $rideRepo;
        $this->userRepository = $userRepo;
        $this->driverRepository = $driverRepo;
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepo;
    }

    /**
     * Display a listing of the Ride.
     *
     * @param RideDataTable $rideDataTable
     *
     * @return Response
     */
    public function index(RideDataTable $rideDataTable)
    {
        return $rideDataTable->render('admin.rides.index');
    }

    /**
     * Show the form for creating a new Ride.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.rides.create');
    }

    /**
     * Store a newly created Ride in storage.
     *
     * @param CreateRideRequest $request
     *
     * @return Response
     */
    public function store(CreateRideRequest $request)
    {
        $input = $request->all();

        $ride = $this->rideRepository->create($input);

        Flash::success('Ride saved successfully.');

        return redirect(route('admin.rides.index'));
    }

    /**
     * Display the specified Ride.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $ride = $this->rideRepository->with(['user', 'offlinePaymentMethod', 'driver', 'driver.user'])->find($id);

        if (empty($ride)) {
            Flash::error('Ride not found');

            return redirect(route('admin.rides.index'));
        }

        return view('admin.rides.show')->with('ride', $ride);
    }

    /*
     * Get ride address and status of ride for each status
     * The admin can get any ride on this
     */
    public function ajaxGetAddressesHtml(Request $request)
    {
        $ride = Ride::where('id', $request->ride_id)->firstOrFail();
        return view('rides.ajax.addresses', compact('ride'));
    }

    /**
     * Show the form for editing the specified Ride.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $ride = $this->rideRepository->find($id);
        $customer = $this->userRepository->where('users.id', $ride->user_id)->pluck('name', 'id');
        $driver = $this->driverRepository->join('users', 'users.id', '=', 'drivers.user_id')->where('drivers.id', $ride->driver_id)->pluck('users.name', 'drivers.id');
        $offlinePaymentMethods = $this->offlinePaymentMethodRepository->pluck('name', 'id');
        if (empty($ride)) {
            Flash::error('Ride not found');

            return redirect(route('admin.rides.index'));
        }

        return view('admin.rides.edit')->with(compact('ride', 'customer', 'driver', 'offlinePaymentMethods'));
    }

    /**
     * Update the specified Ride in storage.
     *
     * @param int $id
     * @param UpdateRideRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRideRequest $request)
    {
        if ($request->offline_payment_method_id == 0 && empty($request->payment_gateway)) {
            return redirect()->back()->withErrors([
                'offline_payment_method_id' => __('Please select offline payment method or one payment gateway'),
            ])->withInput($request->all());
        }

        //check the pickup and ride locations
        $boardingAddress = json_decode($request->boarding_address_data ?? "{}");
        $destinationAddress = json_decode($request->destination_address_data ?? "{}");
        if (!(isset($boardingAddress->geometry->location->lat) && isset($boardingAddress->geometry->location->lng))) {
            return redirect()->back()->withErrors([
                'boarding_address_data' => __('Please insert and select a valid pickup location'),
            ])->withInput($request->all());
        }
        if (!(isset($destinationAddress->geometry->location->lat) && isset($destinationAddress->geometry->location->lng))) {
            return redirect()->back()->withErrors([
                'destination_address_data' => __('Please insert and select a valid destination location'),
            ])->withInput($request->all());
        }
        $ride = $this->rideRepository->find($id);
        if (empty($ride)) {
            Flash::error('Ride not found');

            return redirect(route('admin.rides.index'));
        }
        $updateData = [
            'user_id' => $request->user_id,
            'driver_id' => $request->driver_id,
            'boarding_location' => $request->boarding_location,
            'boarding_location_data' => $request->boarding_address_data,
            'save_boarding_location_for_next_ride' => $request->save_boarding_location_for_next_ride,
            'destination_location_data' => $request->destination_address_data,
            'distance' => $request->distance,
            'driver_value' => $request->driver_value,
            'app_value' => $request->app_value,
            'total_value' => $request->driver_value + $request->app_value,
            'customer_observation' => $request->customer_observation,
            'offline_payment_method_id' => $request->offline_payment_method_id,
            'payment_gateway' => $request->payment_gateway,
            'payment_status' => $request->payment_status,
            'payment_status_date' => ($request->payment_status != $ride->payment_staus) ? now() : $ride->payment_status_date,
            'ride_status' => $request->ride_status,
            'ride_status_date' => ($request->ride_status != $ride->ride_status) ? now() : $ride->ride_status_date,
        ];

        $ride = $this->rideRepository->update($updateData, $id);

        Flash::success('Ride updated successfully.');

        return redirect(route('admin.rides.index'));
    }

    /**
     * Remove the specified Ride from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ride = $this->rideRepository->find($id);

        if (empty($ride)) {
            Flash::error('Ride not found');

            return redirect(route('admin.rides.index'));
        }

        if ($ride->payment_status == "paid" && !$ride->offline_payment_method_id) {
            //Refund the ride based on the payment method
            $this->rideRepository->refundOrderPayment($ride);
        }

        $this->rideRepository->delete($id);

        Flash::success('Ride deleted successfully.');

        return redirect(route('admin.rides.index'));
    }
}

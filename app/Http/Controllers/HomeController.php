<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Ride;
use App\Models\VehicleType;
use App\Repositories\OfflinePaymentMethodRepository;
use App\Repositories\UploadRepository;

class HomeController extends AppBaseController
{

    private $offlinePaymentMethodRepository;

    public function __construct(OfflinePaymentMethodRepository $offlinePaymentMethodRepository)
    {
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepository;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $lastCollectAddressData = '';
        $lastCollectAddress = '';
        if ($user) {
            $lastRide = Ride::select(['save_boarding_location_for_next_ride', 'boarding_location_data', 'boarding_location'])->where('user_id', $user->id)->orderBy('id', 'desc')->first();
            if ($lastRide && $lastRide->save_boarding_location_for_next_ride) {
                $lastCollectAddressData = $lastRide->boarding_location_data;
                $lastCollectAddress = $lastRide->boarding_location;
            }
        }

        $offlinePaymentMethods = $this->offlinePaymentMethodRepository->all();
        $background_image = (new UploadRepository(app()))->getByUuid(setting('background_image', ''));
        if ($background_image && $background_image->hasMedia('default')) {
            $background_image = $background_image->getFirstMediaUrl('default');
        }
        $vehicle_types = VehicleType::all();

        return view('home', compact('background_image', 'lastCollectAddressData', 'lastCollectAddress', 'offlinePaymentMethods', 'vehicle_types'));
    }
}

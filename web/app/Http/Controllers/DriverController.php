<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Ride;
use App\Repositories\DriverRepository;
use App\Repositories\OfflinePaymentMethodRepository;
use App\Repositories\UploadRepository;

class DriverController extends AppBaseController
{

    private $driverRepository;
    private $offlinePaymentMethodRepository;
    public function __construct(DriverRepository $driverRepository, OfflinePaymentMethodRepository $offlinePaymentMethodRepository)
    {
        $this->driverRepository = $driverRepository;
        $this->offlinePaymentMethodRepository = $offlinePaymentMethodRepository;
    }

    /*
     * Driver screen to ride directly to him
     * @parameter string $slug
     */
    public function index($slug)
    {
        try {
            $driver = $this->driverRepository->with('user')->findByField('slug', $slug)->firstOrFail();
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

            return view('drivers.index', compact('background_image', 'driver', 'lastCollectAddressData', 'lastCollectAddress', 'offlinePaymentMethods'));
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('home');
        }
    }
}

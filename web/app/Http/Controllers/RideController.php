<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use App\Models\Ride;
use App\Repositories\DriverRepository;
use App\Repositories\RideRepository;
use App\Repositories\UploadRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;
use Stripe\Stripe;

class RideController extends AppBaseController
{
    private $rideRepository;
    private $deliverBoyRepository;

    public function __construct(RideRepository $rideRepository, DriverRepository $deliverBoyRepository)
    {
        $this->rideRepository = $rideRepository;
        $this->deliverBoyRepository = $deliverBoyRepository;
    }

    /*
    * User rides list
    */
    public function index()
    {

        if (!auth()->check()) {
            return redirect('/');
        }
        $rides = Ride::with(['driver', 'driver.user', 'OfflinePaymentMethod'])->where('user_id', auth()->user()->id)->orderBy('id', 'desc')->get();
        $background_image = (new UploadRepository(app()))->getByUuid(setting('background_image', ''));
        if ($background_image && $background_image->hasMedia('default')) {
            $background_image = $background_image->getFirstMediaUrl('default');
        }
        return view('rides.index', compact('background_image', 'rides'));
    }

    /*
     * Show a specific ride
     * @param $id - Ride id
     */
    public function show(int $id, Request $request)
    {
        $ride = $this->rideRepository->with(['driver', 'driver.user', 'OfflinePaymentMethod'])->find($id);
        if ($ride->user_id != auth()->user()->id) {
            abort(403);
        }
        $background_image = (new UploadRepository(app()))->getByUuid(setting('background_image', ''));
        if ($background_image && $background_image->hasMedia('default')) {
            $background_image = $background_image->getFirstMediaUrl('default');
        }
        if (!$ride->offline_payment_method_id) {
            /*
             * Here update the payment intent if necessary to dont show old information for customer
             * But It doesn't substitute the webhooks updates, it's just for update the status quickly and show for the customer we can use it
             */
            if ($ride->payment_gateway == 'stripe' && $request->get('payment_intent')) {
                $this->rideRepository->updateStripePaymentStatusByGatewayId($request->get('payment_intent'));
                return redirect(route('rides.show', $id));
            } elseif ($ride->payment_gateway == 'paypal' && $request->get('payment_gateway_id')) {
                $this->rideRepository->updatePaypalPaymentStatusByGatewayId($request->get('payment_gateway_id'));
                return redirect(route('rides.show', $id));
            }else if($ride->payment_gateway == 'flutterwave' && $request->get('transaction_id')){
                $this->rideRepository->updateFlutterwavePaymentStatusByGatewayId($request->get('transaction_id'));
                return redirect(route('rides.show',$id));
            }else if($ride->payment_gateway == 'razorpay' && $request->get('razorpay_payment_id')){
                $this->rideRepository->updateRazorpayPaymentStatusByGatewayId($request->get('razorpay_payment_id'));
                return redirect(route('rides.show',$id));
            }
        }

        return view('rides.show', compact('background_image', 'ride'));
    }

    /*
     * Get ride address and status of ride for each status
     */
    public function ajaxGetAddressesHtml(Request $request)
    {
        $ride = Ride::where('user_id', auth()->user()->id)->where('id', $request->ride_id)->firstOrFail();
        return view('rides.ajax.addresses', compact('ride'));
    }
}

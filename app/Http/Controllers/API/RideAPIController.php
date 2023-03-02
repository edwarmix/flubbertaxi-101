<?php

namespace App\Http\Controllers\API;

use App\Models\Driver;
use App\Models\Ride;
use App\Repositories\DriverRepository;
use App\Repositories\RideRepository;
use App\Repositories\UploadRepository;
use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Razorpay\Api\Api;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;

class RideAPIController extends Controller
{
    private $rideRepository;
    private $driverRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RideRepository $rideRepository, DriverRepository $driverRepository)
    {
        $this->rideRepository = $rideRepository;
        $this->driverRepository = $driverRepository;
    }


    /**
     * Display a listing of the Rides.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'required_with:current_item|integer|min:1',
            'current_item' => 'required_with:limit|integer|min:0'
        ]);
        try {
            $hasMoreRides = false;
            $ridesQuery = Ride::where('user_id', Auth::user()->id)->with('driver.user', 'offlinePaymentMethod')->orderBy('id', 'desc');

            if ($request->has('limit')) {
                $hasMoreRides = $ridesQuery
                    ->count() > ($request->current_item + $request->limit);

                $ridesQuery->skip($request->current_item)
                    ->take($request->limit);
            }

            $rides = $ridesQuery->get()->toArray();

            Carbon::setlocale(config('app.locale'));
            foreach ($rides as $key => $ride) {
                $rides[$key]['created_at'] = (new Carbon($ride['created_at']))->tz(app()->config->get('app.timezone'))->format('Y-m-d H:i:s');
            }

            return $this->sendResponse(['has_more_rides' => $hasMoreRides, 'rides' => $rides], 'Rides retrieved successfully');
        } catch (\Exception $e) {
            report($e);
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified Ride.
     * GET|HEAD /rides/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Ride $ride */
        $ride = $this->rideRepository->with(['driver.user', 'offlinePaymentMethod'])->find($id);

        if (empty($ride) || $ride->user_id != auth()->user()->id) {
            return $this->sendError('Ride not found');
        }

        return $this->sendResponse($ride->toArray(), 'Ride retrieved successfully');
    }

    /*
     * Simulate the ride pricing
     */
    public function simulate(Request $request)
    {
        $boardingAddress = json_decode($request->boarding_address_data ?? "{}");
        $destinationAddress =  json_decode($request->destination_address_data ?? "{}");
        $ridePlaceTmp = json_decode($request->ride_address_tmp ?? "{}");
        if ((!(isset($boardingAddress->geometry->location->lat) && isset($boardingAddress->geometry->location->lng)))
            || (!(isset($destinationAddress->geometry->location->lat) && isset($destinationAddress->geometry->location->lng)) && !(isset($ridePlaceTmp->geometry->location->lat) && isset($ridePlaceTmp->geometry->location->lng)))
            || (!(isset($request->vehicle_type) && VehicleType::find($request->vehicle_type)->exists()))
        ) {
            //dont filled everything needed to simulate
            return [
                'success' => 1,
                'enabled' => false,
            ];
        }
        $vehicle_type = VehicleType::find($request->vehicle_type);
        $totalValue = 0;
        $distance = 0;

        if (!(isset($boardingAddress->geometry->location->lat) && isset($boardingAddress->geometry->location->lng))) {
            return [
                'success' => 0,
                'message' => __('Pickup address is not valid'),
                'price' => $totalValue,
                'distance' => $distance
            ];
        }

        if (!(isset($destinationAddress->geometry->location->lat) && isset($destinationAddress->geometry->location->lng))) {
            return [
                'success' => 0,
                'message' => __('Destination address is not valid'),
                'price' => $totalValue,
                'distance' => $distance
            ];
        }
        $drivers =  $this->driverRepository->getDriversNearOf($boardingAddress->geometry->location->lat, $boardingAddress->geometry->location->lng)->where('vehicle_type_id', $vehicle_type->id);

        if ($drivers->isEmpty()) {
            return [
                'success' => 0,
                'message' => __('No Drivers near by'),
                'price' => $totalValue,
                'distance' => $distance
            ];
        }

        $gmaps = new \yidas\googleMaps\Client(['key' => setting('google_maps_key')]);

        $pickupPlaceString = $boardingAddress->geometry->location->lat . "," . $boardingAddress->geometry->location->lng;

        $destinationAddressString = $destinationAddress->geometry->location->lat . "," . $destinationAddress->geometry->location->lng;
        $distanceMatrix = $gmaps->distanceMatrix($pickupPlaceString, $destinationAddressString, [
            'mode' => 'driving',
            'units' => ((setting('distance_unit', 'mi') == 'mi') ? 'imperial' : 'metric')
        ]);
        if ($distanceMatrix['status'] != 'OK') {
            return [
                'success' => 0,
                'message' => __('Error getting distance matrix'),
                'price' => 0,
                'distance' => 0
            ];
        }
        $distance += $distanceMatrix['rows'][0]['elements'][0]['distance']['value'] / 1000;

        $billDistance = $distance;

        if ($vehicle_type->base_distance > 0) {
            $billDistance = $billDistance - $vehicle_type->base_distance;
            if ($billDistance < 0) {
                $billDistance = 0;
            }
        }
        $totalValue = ($vehicle_type->base_price ?? 0) + ($billDistance * $vehicle_type->additional_distance_pricing ?? 0);


        $totalValue += $totalValue * ($vehicle_type->app_tax ?? 0) / 100;

        return [
            'success' => 1,
            'price' => getPrice($totalValue),
            'originalPrice' => $totalValue,
            'originalDistance' => $distance,
            'distance' => number_format($distance, 2) . ' ' . setting('distance_unit', 'mi'),
            'enabled' => ($totalValue > 0 && $distance > 0)
        ];
    }

    /*
     * Store an ride
     */
    public function store(Request $request)
    {
        $simulation = $this->simulate($request);
        if ($simulation['success'] && $simulation['enabled']) {
            $boardingAddress = json_decode($request->boarding_address_data ?? "{}");
            $destinationAddress = json_decode($request->destination_address_data  ?? "{}");
            $drivers =  $this->driverRepository->getDriversNearOf($boardingAddress->geometry->location->lat, $boardingAddress->geometry->location->lng)->where('vehicle_type_id', ($request->vehicle_type_id ?? $request->vehicle_type));

            if ($drivers->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'active' => false,
                    'message' => __('No Drivers available')
                ], 400);
            }

            $vehicle_type = VehicleType::find($request->vehicle_type);

            $totalPrice = $simulation['originalPrice'];
            $appTax = $vehicle_type->app_tax ?? 0;
            $driverPrice = round(getNumberBeforePercentage($totalPrice, $appTax), 2, PHP_ROUND_HALF_DOWN);

            $appPrice = $totalPrice - $driverPrice;

            $paymentMethodType = $request->payment_method_type;
            $paymentMethodId = $request->payment_method;

            //create ride
            try {
                $ride = Ride::create([
                    'user_id' => auth()->user()->id,
                    'driver_id' => $drivers->first()->id,
                    'vehicle_type_id' => $request->vehicle_type,
                    'boarding_location' => $request->boarding_location,
                    'boarding_location_data' => json_encode($boardingAddress),
                    'save_boarding_location_for_next_ride' => $request->save_data ?? false,
                    'destination_location_data' => json_encode($destinationAddress),
                    'distance' => $simulation['originalDistance'],
                    'driver_value' => $driverPrice,
                    'app_value' => $appPrice,
                    'total_value' => $totalPrice,
                    'customer_observation' => $request->observation ?? null,
                    'offline_payment_method_id' => (($paymentMethodType == 'offline') ? $paymentMethodId : 0),
                    'payment_gateway' => (($paymentMethodType == 'offline') ? null : $paymentMethodId),
                    'gateway_id' => null,
                    'payment_status' => 'pending',
                    'payment_status_date' => Carbon::now(),
                    'ride_status' => (($paymentMethodType == 'offline') ? 'pending' : 'waiting'),
                    'ride_status_date' => Carbon::now(),
                    'assigned_drivers' => json_encode([$drivers->first()->id]),
                    'driver_assigned_date' => Carbon::now(),
                ]);
            } catch (\Exception $e) {
                report($e);
                return response()->json(['success' => false, 'active' => true, 'message' => $e->getMessage()], 400);
            }
            if ($ride->id) {
                return response()->json([
                    'success' => true,
                    'active' => false,
                    'id' => $ride->id
                ], 200);
            }
        } else {
            //return the simulation error
            return $simulation;
        }
    }

    /*
     * Create a payment intent for stripe
     */
    public function initializePayment(Request $request)
    {
        $ride = Ride::findOrFail($request->ride_id);
        if ($ride->payment_gateway == 'stripe') {
            if (isset($ride->gateway_id)) {
                $paymentIntent = \Stripe\PaymentIntent::retrieve($ride->gateway_id);
                return ['clientSecret' => $paymentIntent->client_secret];
            }
            $intent = \Stripe\PaymentIntent::create([
                'amount' => intval($ride->total_value * 100),
                'currency' => setting('currency'),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'metadata' => [
                    'ride_id' => $ride->id,
                ],
            ]);
            $ride->gateway_id = $intent->id;
            $ride->save();
            return ['clientSecret' => $intent->client_secret];
        } elseif ($ride->payment_gateway == 'mercado_pago') {
            \MercadoPago\SDK::setAccessToken(setting('mercado_pago_secret'));

            $item = new \MercadoPago\Item();
            $item->title = __('Ride') . ' #' . $ride->id . ' of ' . $ride->distance . ' ' . setting('distance_unit', 'mi');
            $item->quantity = 1;
            $item->unit_price = $ride->total_value;
            $item->category_id = "virtual_goods";
            $item->currency_id = "BRL";

            $preference = new \MercadoPago\Preference();
            $preference->items = array($item);
            $preference->external_reference = $ride->id;
            $preference->back_urls = array(
                "success" => url('/rides/') . "/" . $ride->id,
                "failure" => url('/rides/') . "/" . $ride->id,
                "pending" => url('/rides/') . "/" . $ride->id
            );

            $preference->auto_return = "approved";
            $preference->notification_url = URL::to('api/webhook/mercadopago');
            $preference->payment_methods = array(
                "installments" => 1
            );
            $preference->save();
            return ['preference' => $preference->id];
        } else if ($ride->payment_gateway == 'razorpay') {
            if (isset($ride->gateway_order_id)) {
                return ['payment_ride_id' => $ride->gateway_order_id];
            }
            $orderOptions = array(
                'receipt' => $ride->id,
                'amount' => intval($ride->total_value * 100),
                'currency' => 'INR',
                'notes' => array('ride_id' => $ride->id),
            );

            $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
            $paymentOrder = $api->order->create($orderOptions);
            $ride->gateway_order_id = $paymentOrder->id;
            $ride->save();
            return ['payment_order_id' => $paymentOrder->id];
        }
        return ['message' => __('Payment method not supported')];
    }

    /*
     * Cancel an ride if it's pending yet
     */
    public function cancel(Request $request)
    {
        $ride = $this->rideRepository->find($request->ride_id);
        if ($ride->user_id != auth()->user()->id || !($ride->ride_status == 'pending' || $ride->ride_status == 'waiting')) {
            return [
                'success' => 0,
                'message' => __("You cannot cancel this ride as it has already been accepted. Contact the driver for more information.")
            ];
        }
        if ($ride->payment_status == "paid") {

            if (!$ride->offline_payment_method_id) {
                //Refund the ride based on the payment method
                $this->orderRepository->refundRidePayment($ride);
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            } else {
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            }
        }
        $ride->ride_status = "cancelled";
        $ride->ride_status_date = Carbon::now();
        $ride->save();

        return [
            'success' => 1
        ];
    }


    public function getDriverPosition(Request $request)
    {
        $ride = $this->rideRepository->with('driver')->find($request->ride_id);
        if ($ride->user_id != auth()->user()->id || !in_array($ride->ride_status, ['accepted', 'in_progress', 'delivered'])) {
            return [
                'success' => 0,
                'message' => __("You cannot see the driver position")
            ];
        }

        return [
            'success' => 1,
            'lat' => (float)$ride->driver->lat,
            'lng' => (float)$ride->driver->lng
        ];
    }
    public function payWithPayPal(Request $request, $id)
    {
        $ride = Ride::find($id);

        if (!isset($ride) || ($ride->payment_gateway != "paypal" && $ride->payment_gateway != "PayPal") || $ride->payment_status != "pending") {
            abort(404);
        }
        return view('rides.api.paypal_payment', compact('ride'));
    }

    public function payWithMercadoPago(Request $request, $id)
    {
        $ride = Ride::find($id);

        if (!isset($ride) || $ride->payment_gateway != "mercado_pago" || $ride->payment_status != "pending") {
            abort(404);
        }
        return view('rides.api.mercado_pago_payment', compact('ride'));
    }

    public function payWithFlutterwave(Request $request, $id)
    {
        $ride = Ride::find($id);

        $this->uploadRepository = new UploadRepository(app());
        $upload = $this->uploadRepository->getByUuid(setting('app_logo', ''));
        $appLogo = asset('img/logo_default.png');
        if ($upload && $upload->hasMedia('default')) {
            $appLogo = $upload->getFirstMediaUrl('default');
        }
        if (!isset($ride) || $ride->payment_gateway != "flutterwave" || $ride->payment_status != "pending") {
            abort(404);
        }
        return view('rides.api.flutterwave_payment', compact('ride', 'appLogo'));
    }

    public function payWithRazorpay(Request $request, $id)
    {
        $ride = Ride::find($id);

        $this->uploadRepository = new UploadRepository(app());
        $upload = $this->uploadRepository->getByUuid(setting('app_logo', ''));
        $appLogo = asset('img/logo_default.png');
        if ($upload && $upload->hasMedia('default')) {
            $appLogo = $upload->getFirstMediaUrl('default');
        }
        if (!isset($ride) || $ride->payment_gateway != "razorpay" || $ride->payment_status != "pending") {
            abort(404);
        }
        return view('rides.api.razorpay_payment', compact('ride', 'appLogo'));
    }

    public function checkPaymentByRideID($id)
    {
        try {
            $ride = Ride::findOrFail($id);

            return $this->sendResponse($ride->payment_status, 'Payment ride retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /*
     * Check the ride status and change the driver if necessary
     */
    public function checkStatus($id)
    {
        try {
            $ride = Ride::findOrFail($id);
            Ride::updateNextDriver($ride, $this->driverRepository);
            return $this->sendResponse($ride->ride_status, __('Ride status retrieved successfully'));
        } catch (Exception $e) {
            report($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function paymentSuccessScreen($id, Request $request)
    {
        $ride = $this->rideRepository->find($id);
        if ($ride->user_id != auth()->user()->id) {
            abort(403);
        }
        if (!$ride->offline_payment_method_id) {
            /*
            * Here update the payment intent if necessary to dont show old information for customer
            * But It doesn't substitute the webhooks updates, it's just for update the status quickly and show for the customer we can use it
            */
            if ($ride->payment_gateway == 'stripe' && $request->get('payment_intent')) {
                $this->rideRepository->updateStripePaymentStatusByGatewayId($request->get('payment_intent'));
            } elseif ($ride->payment_gateway == 'paypal' && $request->get('payment_gateway_id')) {
                $this->rideRepository->updatePaypalPaymentStatusByGatewayId($request->get('payment_gateway_id'));
            } else if ($ride->payment_gateway == 'flutterwave' && $request->get('transaction_id')) {
                $this->rideRepository->updateFlutterwavePaymentStatusByGatewayId($request->get('transaction_id'));
            } else if ($ride->payment_gateway == 'razorpay' && $request->get('razorpay_payment_id')) {
                $this->rideRepository->updateRazorpayPaymentStatusByGatewayId($request->get('razorpay_payment_id'));
            }
        }

        return view('rides.api.success', compact('ride'));
    }
}

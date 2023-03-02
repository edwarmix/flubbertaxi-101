<?php

namespace App\Repositories;

use App\Models\Ride;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

/**
 * Class RideRepository
 * @package App\Repositories
 * @version July 12, 2022, 12:01 pm UTC
 */

class RideRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'mid',
        'user_id',
        'driver_id',
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
        'gateway_id',
        'payment_status',
        'payment_status_date',
        'ride_status',
        'ride_status_date',
        'status_observation'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Ride::class;
    }


    /*
     * Find the payment intent on stripe and update the ride if necessary
     */
    public function updateStripePaymentStatusByGatewayId($gatewayId)
    {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($gatewayId);
        $rideId = $paymentIntent->metadata->ride_id ?? 0;
        $ride = Ride::find($rideId);
        if ($ride) {
            if ($paymentIntent->status == 'succeeded') {


                if ($ride && $ride->payment_status == 'pending') {
                    $ride->gateway_id = $paymentIntent->id;
                    $ride->payment_status = 'paid';
                    $ride->payment_status_date = Carbon::now();
                    if ($ride->ride_status == 'waiting') {
                        $ride->ride_status = 'pending';
                        $ride->ride_status_date = Carbon::now();
                    }
                    $ride->save();
                }
            } else {
                if ($ride->payment_status == 'paid' && $ride->gateway_id == $paymentIntent->id) {
                    $ride->payment_status = 'cancelled';
                    $ride->payment_status_date = Carbon::now();
                    $ride->save();
                }
            }
        }
    }

    /*
     * Refund a specific stripe intent
     */
    public function refundStripePayment($gatewayId)
    {
        $paymentIntent = \Stripe\PaymentIntent::retrieve($gatewayId);
        if ($paymentIntent->status == 'succeeded') {
            $paymentIntent->refund();
        }
    }


    /*
     * Find the payment by id on paypal and update the ride if necessary
     */
    public function updatePaypalPaymentStatusByGatewayId($gatewayId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, setting('paypal_key') . ":" . setting('paypal_secret'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $result = curl_exec($ch);
        if ($result) {
            $result = json_decode($result);
            $token = $result->access_token;
            curl_close($ch);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v2/payments/captures/" . $gatewayId);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            if ($result) {
                $result = json_decode($result, true);
                if (isset($result['custom_id'])) {
                    $rideId = $result['custom_id'];
                    $ride = Ride::find($rideId);
                    if ($result['status'] == 'COMPLETED') {

                        if ($ride && $ride->payment_status == 'pending') {
                            $ride->gateway_id = $gatewayId;
                            $ride->payment_status = 'paid';
                            $ride->payment_status_date = Carbon::now();
                            if ($ride->ride_status == 'waiting') {
                                $ride->ride_status = 'pending';
                                $ride->ride_status_date = Carbon::now();
                            }
                            $ride->save();
                        }
                    } elseif ($ride && $ride->payment_status == 'paid') {
                        $ride->gateway_id = $gatewayId;
                        $ride->payment_status = 'cancelled';
                        $ride->payment_status_date = Carbon::now();
                        $ride->save();
                    }
                }
            }
        }
    }

    /*
     * Refund a specific payment on paypal
     */
    public function refundPaypalPayment($gatewayId)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v1/oauth2/token");
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, setting('paypal_key') . ":" . setting('paypal_secret'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        $result = curl_exec($ch);
        if ($result) {
            $result = json_decode($result);
            $token = $result->access_token;
            curl_close($ch);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_URL, "https://api-m.paypal.com/v2/payments/captures/" . $gatewayId . '/refund');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            ));
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            $result = curl_exec($ch);
            curl_close($ch);
        }
    }

    /*
     * Find the payment by id on mercado pago and update the ride if necessary
     */
    public function updateMercadoPagoPaymentStatusByGatewayId($gatewayId)
    {
        \MercadoPago\SDK::setAccessToken(setting('mercado_pago_secret'));
        $payment = \MercadoPago\Payment::get($gatewayId);
        if ($payment) {
            $rideId = $payment->external_reference;
            $ride = Ride::find($rideId);
            if ($ride) {
                if ($payment->status == 'approved') {
                    if ($ride->payment_status == 'pending') {
                        $ride->gateway_id = $gatewayId;
                        $ride->payment_status = 'paid';
                        $ride->payment_status_date = Carbon::now();
                        if ($ride->ride_status == 'waiting') {
                            $ride->ride_status = 'pending';
                            $ride->ride_status_date = Carbon::now();
                        }
                        $ride->save();
                    }
                } elseif ($ride->payment_status == 'paid') {
                    $ride->gateway_id = $gatewayId;
                    $ride->payment_status = 'cancelled';
                    $ride->payment_status_date = Carbon::now();
                    $ride->save();
                }
            }
        }
    }

    /*
     * Refund a specific payment on mercado pago
     */
    public function refundMercadoPagoPayment($gatewayId)
    {
        \MercadoPago\SDK::setAccessToken(setting('mercado_pago_secret'));
        $payment = \MercadoPago\Payment::get($gatewayId);
        if ($payment) {
            $payment->refund();
        }
    }


     /*
     * Find the payment by id on Flutterwave and update the ride if necessary
     */
    public function updateFlutterwavePaymentStatusByGatewayId($gatewayId)
    {
        $response = Http::withToken(setting('flutterwave_secret'))->get('https://api.flutterwave.com/v3/transactions/' . $gatewayId . '/verify')->json();
        if ($response['status'] == 'success') {
            $rideId = $response['data']['meta']['ride_id'];
            $ride = Ride::find($rideId);
            if ($ride) {
                if ($response['data']['status'] == "successful") {
                    if ($response['data']['amount'] == $ride->total_value && $ride->payment_status == 'pending') {
                        $ride->gateway_id = $gatewayId;
                        $ride->payment_status = 'paid';
                        $ride->payment_status_date = Carbon::now();
                        if ($ride->ride_status == 'waiting') {
                            $ride->ride_status = 'pending';
                            $ride->ride_status_date = Carbon::now();
                        }
                        $ride->save();
                    }
                } else {
                    if ($ride->payment_status == 'paid' && $ride->gateway_id == $gatewayId) {
                        $ride->payment_status = 'cancelled';
                        $ride->payment_status_date = Carbon::now();
                        $ride->save();
                    }
                }
            }
        }
    }

    /*
    * Find the payment by id on Razorpay and update the order if necessary
    */
    public function updateRazorpayPaymentStatusByGatewayId($paymentId)
    {
        $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
        $payment = $api->payment->fetch($paymentId);
        if (isset($payment)) {
            $rideId = $payment->notes['ride_id'];
            $ride = Ride::find($rideId);
            if ($ride) {
                if ($payment->status == "captured") {
                    if ($payment->amount == intval($ride->total_value * 100) && $ride->payment_status == 'pending') {
                        $ride->gateway_id = $paymentId;
                        $ride->payment_status = 'paid';
                        $ride->payment_status_date = Carbon::now();
                        if ($ride->ride_status == 'waiting') {
                            $ride->ride_status = 'pending';
                            $ride->ride_status_date = Carbon::now();
                        }
                        $ride->save();
                    }
                } else {
                    if ($ride->payment_status == 'paid' && $ride->gateway_id == $paymentId) {
                        $ride->payment_status = 'cancelled';
                        $ride->payment_status_date = Carbon::now();
                        $ride->save();
                    }
                }
            }
        }
    }

     /*
     * Refund a specific payment on Flutterwave
     */
     public function refundFlutterwavePayment($gatewayId, $amount)
     {
         Http::withToken(setting('flutterwave_secret'))->post('https://api.flutterwave.com/v3/transactions/' . $gatewayId . '/refund',[
             'amount' => $amount,
         ])->json();
     }

     /*
     * Refund a specific payment on Razorpay
     */
     public function refundRazorpayPayment($gatewayId)
     {
         $api = new Api(setting('razorpay_key'), setting('razorpay_secret'));
         $api->payment->fetch($gatewayId)->refund(array("speed"=>"optimum"));
     }

     public function refundRidePayment(Ride $ride){
        if (!$ride->offline_payment_method_id && isset($ride->gateway_id)) {
            //Refund the ride based on the payment method
            if ($ride->payment_gateway == 'stripe') {
                $this->rideRepository->refundStripePayment($ride->gateway_id);
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            } elseif ($ride->payment_gateway == 'paypal') {
                $this->rideRepository->refundPaypalPayment($ride->gateway_id);
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            } elseif ($ride->payment_gateway == 'mercado_pago') {
                $this->rideRepository->refundMercadoPagoPayment($ride->gateway_id);
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            } elseif ($ride->payment_gateway == 'flutterwave') {
                $this->rideRepository->refundFlutterwavePayment($ride->gateway_id, $ride->total_value);
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            } elseif ($ride->payment_gateway == 'razorpay') {
                $this->rideRepository->refundRazorpayPayment($ride->gateway_id);
                $ride->payment_status = "cancelled";
                $ride->payment_status_date = Carbon::now();
            }

        }
    }

}

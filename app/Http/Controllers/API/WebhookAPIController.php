<?php

namespace App\Http\Controllers\API;

use App\Repositories\RideRepository;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebhookAPIController extends Controller
{
    private $rideRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RideRepository $rideRepo)
    {
        $this->rideRepository = $rideRepo;
    }

    public function stripeWebhook(Request $request)
    {
        switch ($request->data['object']['object'] ?? null) {
            case 'payment_intent':
                $id = $request->data['object']['id'];
                $this->rideRepository->updateStripePaymentStatusByGatewayId($id);
                break;
        }
    }

    public function paypalWebhook(Request $request)
    {
        switch ($request->event_type ?? null) {
            case 'PAYMENT.CAPTURE.COMPLETED':
                $id = $request->resource['id'];
                $this->rideRepository->updatePaypalPaymentStatusByGatewayId($id);
                break;
        }
    }

    public function mercadoPagoWebhook(Request $request)
    {
        switch ($request->event_type ?? null) {
            case 'payment':
                $id = $request->resource->id;
                $this->rideRepository->updateMercadoPagoPaymentStatusByGatewayId($id);
                break;
        }
    }

    public function flutterwaveWebhook(Request $request)
    {
        switch ($request->{"event.type"} ?? null) {
            case 'CARD_TRANSACTION':
                $id = $request->id;
                $this->rideRepository->updateFlutterwavePaymentStatusByGatewayId($id);
                break;
        }
    }

    public function razorpayWebhook(Request $request)
    {
        switch ($request->event ?? null) {
            case 'order.paid':
                $id = $request->payload['payment']['entity']['id'];
                $this->orderRepository->updateRazorpayPaymentStatusByGatewayId($id);
                break;
        }
    }
}

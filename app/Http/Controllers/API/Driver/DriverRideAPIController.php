<?php

namespace App\Http\Controllers\API\Driver;

use Exception;
use App\Models\Ride;
use App\Http\Controllers\Controller;
use App\Models\DriverPayout;
use App\Repositories\DriverRepository;
use App\Repositories\RideRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverRideAPIController extends Controller
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
     * Display a listing of the Ride.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'limit' => 'required_with:current_item|integer|min:1',
            'current_item' => 'required_with:limit|integer|min:0',
            'datetime_end' => 'date',
        ]);
        try {
            $hasMoreRides = false;
            $ridesQuery = Ride::where('ride_status', '!=', 'waiting')->orderBy('id', 'desc')->with('offlinePaymentMethod');

            if ($request->has('datetime_start')) {
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', substr($request->datetime_start, 0, 10) . ' 00:00:00');
                $ridesQuery->where('created_at', '>=', $startDate);
            }

            if ($request->has('status')) {
                $ridesQuery->whereIn('ride_status', $request->status);
            }

            if ($request->has('datetime_end')) {
                $dataTerm = Carbon::createFromFormat('Y-m-d H:i:s', substr($request->datetime_end, 0, 10) . ' 23:59:59');

                $ridesQuery->where('created_at', '<=', $dataTerm);
            }

            $ridesQuery->where('driver_id', Auth::user()->driver->id);

            if ($request->has('limit')) {
                $hasMoreRides = $ridesQuery
                    ->count() > ($request->current_item + $request->limit);

                $ridesQuery->skip($request->current_item)
                    ->take($request->limit);
            }

            $rides = $ridesQuery->get();
            $rides = $rides->toArray();
            Carbon::setlocale(config('app.locale'));
            foreach ($rides as $key => $ride) {
                $rides[$key]['created_at'] = (new Carbon($ride['created_at']))->tz(app()->config->get('app.timezone'))->format('Y-m-d H:i:s');
            }

            return $this->sendResponse(['has_more_rides' => $hasMoreRides, 'rides' => $rides], 'Rides retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Display the specified Ride.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $ride = Ride::with('offlinePaymentMethod')->find($id);

            if (!isset($ride) || $ride->driver_id != Auth::user()->driver->id) {
                return $this->sendError(__('Not found'));
            }

            return $this->sendResponse($ride, 'Ride retrieved successfully');
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
    /**
     * Check if exists a new Ride
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNewRide(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|integer|min:0',
        ]);
        try {
            $rides = Ride::where('driver_id', Auth::user()->driver->id)
                ->whereIn('ride_status', ['pending', 'accepted', 'in_progress'])
                ->where('id', '>', $request->ride_id)
                ->orderBy('id', 'desc')
                ->get();

            return $this->sendResponse($rides, __('Rides retrieved successfully'));
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Update the status of a Ride
     *
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'ride_id' => 'required|integer|min:1',
            'status' => 'required|string|in:accepted,rejected,in_progress,collected,delivered,completed,cancelled',
            'ride_address_id' => 'required_if:status,delivered',
        ]);

        $ride = Ride::find($request->ride_id);

        if (!isset($ride) || $ride->driver_id != Auth::user()->driver->id) {
            return $this->sendError('Pedido não encontrado');
        }

        try {
            if ($ride->payment_status == "paid" && !$ride->offline_payment_method_id && $request->status == 'cancelled') {
                $this->rideRepository->refundRidePayment($ride);
            }
            switch ($request->status) {
                case 'rejected':
                    if ($ride->ride_status != 'pending') {
                        return $this->sendError(__('Ride is not pending'));
                    }
                    Ride::updateNextDriver($ride, $this->driverRepository);
                    break;
                case 'accepted':
                    if ($ride->ride_status != 'pending') {
                        return $this->sendError(__('Ride is not pending'));
                    }
                    $ride->ride_status = $request->status;
                    break;
                case 'in_progress':
                    if ($ride->ride_status != 'accepted') {
                        return $this->sendError(__('You start an ride that is not accepted'));
                    }
                    $ride->ride_status = $request->status;
                    break;
                case 'completed':
                    $ride->ride_status = $request->status;
                    break;
                case 'cancelled':
                    $ride->ride_status = $request->status;
                    $ride->payment_status = "cancelled";
                    $ride->payment_status_date = Carbon::now();
                    $ride->ride_status_date = Carbon::now();
                    break;
                default:
                    break;
            }

            $ride->save();

            return $this->sendResponse($ride, __('Ride status updated successfully'));
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }

    /**
     * Return the rides values of the driver
     *
     * @return \Illuminate\Http\Response
     */
    public function values()
    {
        try {
            $ridesDay = Ride::where('driver_id', Auth::user()->driver->id)
                ->where('ride_status', 'completed')
                ->whereBetween('created_at', [Carbon::now()->startOfDay(), Carbon::now()->endOfDay()])
                ->orderBy('id', 'desc')
                ->sum('driver_value');

            $ridesYesterday = Ride::where('driver_id', Auth::user()->driver->id)
                ->where('ride_status', 'completed')
                ->whereBetween('created_at', [Carbon::yesterday()->startOfDay(), Carbon::yesterday()->endOfDay()])
                ->orderBy('id', 'desc')
                ->sum('driver_value');

            $ridesWeek = Ride::where('driver_id', Auth::user()->driver->id)
                ->where('ride_status', 'completed')
                ->whereBetween('created_at', [Carbon::now()->subDays(7)->startOfDay(), Carbon::now()->endOfDay()])
                ->orderBy('id', 'desc')
                ->sum('driver_value');

            $payout = DriverPayout::where('driver_id', Auth::user()->driver->id)
                ->sum('amount');

            $response['today'] = number_format((float)$ridesDay, 2, '.', '');
            $response['yesterday'] = number_format((float)$ridesYesterday, 2, '.', '');
            $response['week'] = number_format((float)$ridesWeek, 2, '.', '');
            $response['pending'] = number_format((float)$payout, 2, '.', '');

            return $this->sendResponse($response, __('Rides values retrieved successfully'));
        } catch (Exception $e) {
            report($e);
            return $this->sendError(trans('error.error'));
        }
    }
}

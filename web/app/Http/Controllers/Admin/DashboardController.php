<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use App\Models\Driver;
use App\Models\Ride;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends AppBaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $statuses = [
            'waiting' => [
                'name' => trans('general.ride_status_list.waiting'),
                'color' => '#dddddd',
                'only_recent' => true,
            ],
            'pending' => [
                'name' => trans('general.ride_status_list.pending'),
                'color' => '#F7CB73',
                'only_recent' => false,
            ],
            'accepted' => [
                'name' => trans('general.ride_status_list.accepted'),
                'color' => '#F29339',
                'only_recent' => false,
            ],
            'in_progress' => [
                'name' => trans('general.ride_status_list.in_progress'),
                'color' => '#1974D3',
                'only_recent' => false,
            ],
            'completed' => [
                'name' => trans('general.ride_status_list.completed'),
                'color' => '#077E8C',
                'only_recent' => true,
            ],
            'cancelled' => [
                'name' => trans('general.ride_status_list.cancelled'),
                'color' => '#D9512C',
                'only_recent' => true,
            ],
        ];

        //numbers in panels
        $ridesInProgressCount = Ride::whereIn('ride_status', ['pending', 'accepted', 'collected', 'delivered'])->count();
        $activeDriversCount = Driver::where('active', 1)->count();
        $customersCount = User::count();

        //last 6 months revenue chart
        $chart = [];
        for ($i = 5; $i >= 0; $i--) {
            $chart[date('m/y', strtotime('-' . $i . ' months'))] = [
                'revenue' => Ride::where('created_at', '>=', date('Y-m-01', strtotime('-' . $i . ' months')))->where('created_at', '<', date('Y-m-01', strtotime('-' . ($i - 1) . ' months')))->whereIn('ride_status', ['completed', 'delivered'])->sum('total_value'),
                'count' => Ride::where('created_at', '>=', date('Y-m-01', strtotime('-' . $i . ' months')))->where('created_at', '<', date('Y-m-01', strtotime('-' . ($i - 1) . ' months')))->whereIn('ride_status', ['completed', 'delivered'])->count(),
            ];
        }



        return view('admin.dashboard', compact('statuses', 'ridesInProgressCount', 'activeDriversCount', 'customersCount', 'chart'));
    }

    /*
     * Return the ride list for the dashboard kanban list
     */
    function ajaxGetRides(Request $request)
    {
        $status = $request->get('status');

        $onlyRecent = $request->get('only_recent', false);
        $rides = Ride::with('user', 'driver', 'driver.user')->where('ride_status', $status);
        if ($onlyRecent) {
            //last 24h hours rides only
            $rides = $rides->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-24 hours')));
        }
        $rides = $rides->orderBy('id', 'desc')->get();

        $toReturn = [];
        foreach ($rides as $ride) {
            $location = json_decode($ride->destination_location_data);
            $destinationLocation = $location->formatted_address . ' - ' .(isset($location->number)?$location->number:' '). ' | ';

            $toReturn[] = [
                'id' => $ride->id,
                'driver_name' => $ride->driver->user->name,
                'customer_name' => $ride->user->name,
                'boarding_location' => $ride->boarding_location,
                'ride_location' => $destinationLocation,
                'distance' => $ride->distance,
                'driver_value' => getPrice($ride->driver_value),
                'app_value' => getPrice($ride->app_value),
                'total' => getPrice($ride->total_value),
                'link' => route('admin.rides.show', $ride->id),
                'created_at' => getDateHumanFormat($ride->created_at, false),
            ];
        }

        return $toReturn;
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class ReportController extends AppBaseController
{

    /*
     * Rides By Date Report
     */
    public function ridesByDate(Request $request)
    {
        if ($request->isMethod('post')) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (empty($start_date) || empty($end_date)) {
                Flash::error(__('Please select start and end date'));
                return redirect()->back();
            }
            $rides = \App\Models\Ride::with(['user', 'driver', 'driver.user'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->orderBy('created_at', 'asc');
            if ($request->get('only_completed')) {
                $rides = $rides->where('ride_status', 'completed');
            }
            $rides = $rides->get();

            return view('admin.reports.rides_by_date', compact('rides'));
        }
        return view('admin.reports.rides_by_date');
    }

    /*
     * Rides By Driver Report
     */
    public function ridesByDriver(Request $request)
    {
        $drivers = \App\Models\Driver::join('users', 'users.id', '=', 'drivers.user_id')->orderBy('users.name', 'asc')->pluck('users.name', 'drivers.id');
        if ($request->isMethod('post')) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (empty($start_date) || empty($end_date)) {
                Flash::error(__('Please select start and end date'));
                return redirect()->back();
            }
            if (empty($request->get('driver_id'))) {
                Flash::error(__('Please select the driver'));
                return redirect()->back();
            }
            $rides = \App\Models\Ride::with(['user', 'driver', 'driver.user'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('driver_id', $request->get('driver_id'))
                ->orderBy('created_at', 'asc');
            if ($request->get('only_completed')) {
                $rides = $rides->where('ride_status', 'completed');
            }
            $rides = $rides->get();

            return view('admin.reports.rides_by_driver', compact('rides', 'drivers'));
        }

        return view('admin.reports.rides_by_driver')->with('drivers', $drivers);
    }


    /*
     * Rides By Customer Report
     */
    public function ridesByCustomer(Request $request)
    {
        $customers = \App\Models\User::orderBy('name', 'asc')->pluck('name', 'id');
        if ($request->isMethod('post')) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            if (empty($start_date) || empty($end_date)) {
                Flash::error(__('Please select start and end date'));
                return redirect()->back();
            }
            if (empty($request->get('customer_id'))) {
                Flash::error(__('Please select the customer'));
                return redirect()->back();
            }
            $rides = \App\Models\Ride::with(['user', 'driver', 'driver.user'])
                ->whereBetween('created_at', [$start_date, $end_date])
                ->where('user_id', $request->get('customer_id'))
                ->orderBy('created_at', 'asc');
            if ($request->get('only_completed')) {
                $rides = $rides->where('ride_status', 'completed');
            }
            $rides = $rides->get();

            return view('admin.reports.rides_by_customer', compact('rides', 'customers'));
        }

        return view('admin.reports.rides_by_customer')->with('customers', $customers);
    }
}

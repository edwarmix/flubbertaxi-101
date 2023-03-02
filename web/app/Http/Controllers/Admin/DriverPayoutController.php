<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DriverPayoutDataTable;
use App\DataTables\DriverPayoutSummaryDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateDriverPayoutRequest;
use App\Http\Requests\UpdateDriverPayoutRequest;
use App\Models\Driver;
use App\Models\OfflinePaymentMethod;
use App\Repositories\DriverPayoutRepository;
use Flash;
use Response;

class DriverPayoutController extends AppBaseController
{
    /** @var DriverPayoutRepository $driverPayoutRepository*/
    private $driverPayoutRepository;

    /** @var Driver $driverRepository */
    private $driverRepository;

    public function __construct(DriverPayoutRepository $driverPayoutRepo, Driver $driverRepo)
    {
        $this->driverPayoutRepository = $driverPayoutRepo;
        $this->driverRepository = $driverRepo;
    }

    /**
     * Display a listing of the DriverPayout.
     *
     * @param DriverPayoutDataTable $driverPayoutDataTable
     *
     * @return Response
     */
    public function index(DriverPayoutDataTable $driverPayoutDataTable, DriverPayoutSummaryDataTable $driverPayoutSummaryDataTable)
    {         
        $onlinePayments = getAvailablePaymentGatewaysArray();

        $offlinePayments = OfflinePaymentMethod::pluck('name', 'id')->toArray();
        $paymentsArray = [
            'all' => 'All Payment Methods',
        ];

        if (count($onlinePayments) > 0) {
            $paymentsArray['Online Payments'] = ['all_online_payments' => 'All Online Payments'] + $onlinePayments;
        }

        if (count($offlinePayments) > 0) {
            $paymentsArray['Offline Payments'] = ['all_offline_payments' => 'All Offline Payments'] + $offlinePayments;
        }

        return view('admin.driver_payouts.index', [
            'paymentsArray' => $paymentsArray,
            'onlinePayments' => $onlinePayments,
            'offlinePayments' => $offlinePayments,
            'driverPayoutDataTable' => $driverPayoutDataTable->html(),
            'driverPayoutSummaryDataTable' => $driverPayoutSummaryDataTable->html()
        ]);
    }

    /*
     * Interact with datatable request for driver payout
     * @param  DriverPayoutDataTable $driverPayoutDataTable
     * @return Response
     */
    public function getDriverPayoutDataTable(DriverPayoutDataTable $driverPayoutDataTable)
    {
        return $driverPayoutDataTable->render('admin.driver_payouts.index');
    }

    /*
     * Interact with datatable request for driver payout
     * @param  DriverPayoutSummaryDataTable $driverPayoutSummaryDataTable
     * @return Response
     */
    public function getDriverPayoutSummaryDataTable(DriverPayoutSummaryDataTable $driverPayoutSummaryDataTable)
    {
        return $driverPayoutSummaryDataTable->render('admin.driver_payouts.index');
    }

    /**
     * Show the form for creating a new DriverPayout.
     *
     * @return Response
     */
    public function create()
    {
        $drivers = $this->driverRepository->join('users', 'users.id', '=', 'drivers.user_id')->orderBy('users.name')->pluck('users.name', 'drivers.id');
        return view('admin.driver_payouts.create')->with('drivers', $drivers);
    }

    /**
     * Store a newly created DriverPayout in storage.
     *
     * @param CreateDriverPayoutRequest $request
     *
     * @return Response
     */
    public function store(CreateDriverPayoutRequest $request)
    {
        $input = $request->all();

        $driverPayout = $this->driverPayoutRepository->create($input);

        Flash::success('Driver Payout saved successfully.');

        return redirect(route('admin.driverPayouts.index'));
    }

    /**
     * Display the specified DriverPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $driverPayout = $this->driverPayoutRepository->find($id);

        if (empty($driverPayout)) {
            Flash::error('Driver Payout not found');

            return redirect(route('admin.driverPayouts.index'));
        }

        return view('admin.driver_payouts.show')->with('driverPayout', $driverPayout);
    }

    /**
     * Show the form for editing the specified DriverPayout.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $driverPayout = $this->driverPayoutRepository->find($id);

        if (empty($driverPayout)) {
            Flash::error('Driver Payout not found');

            return redirect(route('admin.driverPayouts.index'));
        }

        return view('admin.driver_payouts.edit')->with('driverPayout', $driverPayout);
    }

    /**
     * Update the specified DriverPayout in storage.
     *
     * @param int $id
     * @param UpdateDriverPayoutRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDriverPayoutRequest $request)
    {
        $driverPayout = $this->driverPayoutRepository->find($id);

        if (empty($driverPayout)) {
            Flash::error('Driver Payout not found');

            return redirect(route('admin.driverPayouts.index'));
        }

        $driverPayout = $this->driverPayoutRepository->update($request->all(), $id);

        Flash::success('Driver Payout updated successfully.');

        return redirect(route('admin.driverPayouts.index'));
    }

    /**
     * Remove the specified DriverPayout from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $driverPayout = $this->driverPayoutRepository->find($id);

        if (empty($driverPayout)) {
            Flash::error('Driver Payout not found');

            return redirect(route('admin.driverPayouts.index'));
        }

        $this->driverPayoutRepository->delete($id);

        Flash::success('Driver Payout deleted successfully.');

        return redirect(route('admin.driverPayouts.index'));
    }
}

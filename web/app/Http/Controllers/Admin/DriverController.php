<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\DriverDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\VehicleType;
use App\Repositories\DriverRepository;
use Flash;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Response;

class DriverController extends AppBaseController
{
    /** @var DriverRepository $driverRepository*/
    private $driverRepository;

    public function __construct(DriverRepository $driverRepo)
    {
        $this->driverRepository = $driverRepo;
    }

    /**
     * Return a list of the Driver as JSON.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexJson(Request $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        try {
            $this->driverRepository->pushCriteria(new RequestCriteria($request));
            $this->driverRepository->pushCriteria(new LimitOffsetCriteria($request));

            $drivers = $this->driverRepository->join('users', 'users.id', '=', 'drivers.user_id')->select('drivers.id', 'users.name')->get();

            return $this->sendResponse($drivers, 'Products retrieved successfully');
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display a listing of the Driver.
     *
     * @param DriverDataTable $driverDataTable
     *
     * @return Response
     */
    public function index(DriverDataTable $driverDataTable)
    {
        return $driverDataTable->render('admin.drivers.index');
    }

    /**
     * Show the form for creating a new Driver.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.drivers.create');
    }

    /**
     * Store a newly created Driver in storage.
     *
     * @param CreateDriverRequest $request
     *
     * @return Response
     */
    public function store(CreateDriverRequest $request)
    {
        $input = $request->all();

        $driver = $this->driverRepository->create($input);

        Flash::success('Driver saved successfully.');

        return redirect(route('admin.drivers.index'));
    }

    /**
     * Display the specified Driver.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $driver = $this->driverRepository->with(['user'])->find($id);

        if (empty($driver)) {
            Flash::error('Driver not found');

            return redirect(route('admin.drivers.index'));
        }

        return view('admin.drivers.show')->with('driver', $driver);
    }

    /**
     * Show the form for editing the specified Driver.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $driver = $this->driverRepository->find($id);
        $vehicleTypes = VehicleType::pluck('name', 'id');

        if (empty($driver)) {
            Flash::error('Driver not found');

            return redirect(route('admin.drivers.index'));
        }

        return view('admin.drivers.edit')->with('driver', $driver)->with('vehicleTypes', $vehicleTypes);
    }

    /**
     * Update the specified Driver in storage.
     *
     * @param int $id
     * @param UpdateDriverRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDriverRequest $request)
    {
        $driver = $this->driverRepository->find($id);

        if (empty($driver)) {
            Flash::error('Driver not found');

            return redirect(route('admin.drivers.index'));
        }

        $driver = $this->driverRepository->update($request->all(), $id);

        Flash::success('Driver updated successfully.');

        return redirect(route('admin.drivers.index'));
    }

    /**
     * Remove the specified Driver from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $driver = $this->driverRepository->find($id);

        if (empty($driver)) {
            Flash::error('Driver not found');

            return redirect(route('admin.drivers.index'));
        }

        $this->driverRepository->delete($id);

        Flash::success('Driver deleted successfully.');

        return redirect(route('admin.drivers.index'));
    }
}

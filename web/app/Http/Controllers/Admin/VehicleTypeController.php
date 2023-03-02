<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\VehicleTypeDataTable;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateVehicleTypeRequest;
use App\Http\Requests\UpdateVehicleTypeRequest;
use App\Models\VehicleType;
use App\Repositories\VehicleTypeRepository;
use Flash;
use Response;

class VehicleTypeController extends AppBaseController
{
    /** @var VehicleTypeRepository $categoryRepository*/
    private $categoryRepository;

    public function __construct(VehicleTypeRepository $categoryRepo)
    {
        $this->categoryRepository = $categoryRepo;
    }

    /**
     * Display a listing of the VehicleType.
     *
     * @param VehicleTypeDataTable $categoryDataTable
     *
     * @return Response
     */
    public function index(VehicleTypeDataTable $categoryDataTable)
    {
        return $categoryDataTable->render('admin.vehicle_types.index');
    }

    /**
     * Show the form for creating a new VehicleType.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.vehicle_types.create');
    }

    /**
     * Store a newly created VehicleType in storage.
     *
     * @param CreateVehicleTypeRequest $request
     *
     * @return Response
     */
    public function store(CreateVehicleTypeRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }

        $input = $request->all();

        $category = $this->categoryRepository->create($input);

        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($category->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {
                $category->delete();
                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }

            $category->addMedia($request->file('image'))->toMediaCollection('default');
        }

        Flash::success(__('Vehicle Type saved successfully.'));

        return redirect(route('admin.vehicle_types.index'));
    }

    /**
     * Display the specified VehicleType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error(__('Vehicle Type not found'));

            return redirect(route('admin.vehicle_types.index'));
        }

        return view('admin.vehicle_types.show')->with('category', $category);
    }

    /**
     * Show the form for editing the specified VehicleType.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error(__('Vehicle Type not found'));

            return redirect(route('admin.vehicle_types.index'));
        }

        return view('admin.vehicle_types.edit')->with('category', $category);
    }

    /**
     * Update the specified VehicleType in storage.
     *
     * @param int $id
     * @param UpdateVehicleTypeRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateVehicleTypeRequest $request)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }

        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error(__('Vehicle Type not found'));

            return redirect(route('admin.vehicle_types.index'));
        }
        $category = $this->categoryRepository->update($request->all(), $id);


        if ($category->default) {
            VehicleType::where('id', '!=', $id)->update(['default' => false]);
        }

        if ($request->has('image') && !is_null($request->file('image'))) {
            //upload and associate

            $fileName = uniqid($category->id . "-") . "." . $request->file('image')->getClientOriginalExtension();
            $storeFile = $request->file('image')->storeAs('images', $fileName);

            if ($storeFile == false) {

                Flash::error(__('An error occurred uploading the image. Please try again.'));
                return redirect()->back();
            }
            $category->clearMediaCollection('default');
            $category->addMedia($request->file('image'))->toMediaCollection('default');
        }


        Flash::success(__('Vehicle Type updated successfully.'));

        return redirect(route('admin.vehicle_types.index'));
    }

    /**
     * Remove the specified VehicleType from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        if (env('APP_DEMO', false)) {
            Flash::warning(__('This is a demo version. You will not be able to make changes.'));
            return redirect()->back();
        }
        $category = $this->categoryRepository->find($id);

        if (empty($category)) {
            Flash::error(__('Vehicle Type not found'));

            return redirect(route('admin.vehicle_types.index'));
        }

        $this->categoryRepository->delete($id);

        Flash::success(__('Vehicle Type deleted successfully.'));

        return redirect(route('admin.vehicle_types.index'));
    }
}

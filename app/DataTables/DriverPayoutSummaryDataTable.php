<?php

namespace App\DataTables;

use App\Models\Driver;
use App\Models\DriverPayout;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class DriverPayoutSummaryDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        $dataTable = new EloquentDataTable($query);

        return $dataTable
            ->editColumn('driver_value', function ($line) {
                return getPrice($line['driver_value']);
            })
            ->editColumn('app_value', function ($line) {
                return getPrice($line['app_value']);
            })
            ->editColumn('payout_amount', function ($line) {
                return getPrice($line['payout_amount']);
            })->rawColumns(['driver_value', 'app_value', 'payout_amount']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DriverPayout $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DriverPayout $model)
    {

        if (!isset(request()->payment_methods) || !is_array(request()->payment_methods) || (in_array('all',  request()->payment_methods) || count(array_intersect(['all_offline_payments', 'all_online_payments'], request()->payment_methods)) == 2)) {
            $query = Driver::selectRaw('
            drivers.id,
            users.name,
            (SELECT count(rides.id) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid") as rides_count,
            (SELECT SUM(rides.driver_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid") as driver_value,
            (SELECT SUM(rides.app_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid") as app_value,
            (SELECT SUM(driver_payouts.amount) FROM driver_payouts WHERE driver_payouts.driver_id = drivers.id) as payout_amount
            ')
                ->join('users', 'users.id', '=', 'drivers.user_id');
        } elseif (in_array('all_offline_payments', request()->payment_methods)) {
            $ids = array_filter(request()->payment_methods, "is_numeric");
            if (empty($ids)) {
                $ids = [-1];
            }
            $query = Driver::selectRaw('
            drivers.id,
            users.name,
            (SELECT count(rides.id) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IN (\'' . implode("', '", request()->payment_methods) . '\') OR rides.offline_payment_method_id > 0)) as rides_count,
            (SELECT SUM(rides.driver_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IN (\'' . implode("', '", request()->payment_methods) . '\') OR rides.offline_payment_method_id > 0)) as driver_value,
            (SELECT SUM(rides.app_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IN (\'' . implode("', '", request()->payment_methods) . '\') OR rides.offline_payment_method_id > 0)) as app_value,
            (SELECT SUM(driver_payouts.amount) FROM driver_payouts WHERE driver_payouts.driver_id = drivers.id) as payout_amount
            ')
                ->join('users', 'users.id', '=', 'drivers.user_id');
        } elseif (in_array('all_online_payments', request()->payment_methods)) {
            $ids = array_filter(request()->payment_methods, "is_numeric");
            if (empty($ids)) {
                $ids = [-1];
            }
            $query = Driver::selectRaw('
            drivers.id,
            users.name,
            (SELECT count(rides.id) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IS NOT NULL OR rides.offline_payment_method_id IN (' . implode(", ", $ids) . '))) as rides_count,
            (SELECT SUM(rides.driver_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IS NOT NULL OR rides.offline_payment_method_id IN (' . implode(", ", $ids) . '))) as driver_value,
            (SELECT SUM(rides.app_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IS NOT NULL OR rides.offline_payment_method_id IN (' . implode(", ", $ids) . '))) as app_value,
            (SELECT SUM(driver_payouts.amount) FROM driver_payouts WHERE driver_payouts.driver_id = drivers.id) as payout_amount
            ')
                ->join('users', 'users.id', '=', 'drivers.user_id');
        } else {
            $ids = array_filter(request()->payment_methods, "is_numeric");
            if (empty($ids)) {
                $ids = [-1];
            }
            $query = Driver::selectRaw('
            drivers.id,
            users.name,
            (SELECT count(rides.id) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IN (\'' . implode("', '", request()->payment_methods) . '\') OR rides.offline_payment_method_id IN (' . implode(", ", $ids) . '))) as rides_count,
            (SELECT SUM(rides.driver_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IN (\'' . implode("', '", request()->payment_methods) . '\') OR rides.offline_payment_method_id IN (' . implode(", ", $ids) . '))) as driver_value,
            (SELECT SUM(rides.app_value) FROM rides WHERE rides.driver_id = drivers.id and rides.payment_status = "paid" and (rides.payment_gateway IN (\'' . implode("', '", request()->payment_methods) . '\') OR rides.offline_payment_method_id IN (' . implode(", ", $ids) . '))) as app_value,
            (SELECT SUM(driver_payouts.amount) FROM driver_payouts WHERE driver_payouts.driver_id = drivers.id) as payout_amount
            ')
                ->join('users', 'users.id', '=', 'drivers.user_id');
        }

        return DataTables::of($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('driver-payouts-summary-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.driverPayouts.driverSummary'), null, ["payment_methods" => '$("#payment_methods").val()'])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'),
                [
                    'order' => [[0, 'asc']],
                    'language' => json_decode(
                        file_get_contents(
                            base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ),
                        true
                    ),
                    'buttons' => [
                        'export',
                        'print',
                        'reset',
                        'reload',
                    ],
                ]
            ));
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            [
                'data' => 'name',
                'title' => trans('Driver'),

            ],
            [
                'data' => 'rides_count',
                'title' => trans('Rides Count'),

            ],
            [
                'data' => 'driver_value',
                'title' => trans('Driver Total Value'),

            ],
            [
                'data' => 'app_value',
                'title' => trans('App Total Value'),

            ],
            [
                'data' => 'payout_amount',
                'title' => trans('Payout Total'),

            ],

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'driver_payouts_summary_datatable_' . time();
    }
}

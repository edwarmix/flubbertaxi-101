<?php

namespace App\DataTables;

use App\Models\DriverPayout;
use Yajra\DataTables\Services\DataTable;
use Yajra\DataTables\EloquentDataTable;

class DriverPayoutDataTable extends DataTable
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
            ->editColumn('date', function ($line) {
                return getDateHumanFormat($line['date'], true);
            })
            ->editColumn('amount', function ($line) {
                return getPrice($line['amount']);
            })
            ->addColumn('action', 'admin.driver_payouts.datatables_actions')
            ->rawColumns(['action', 'amount', 'date']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\DriverPayout $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(DriverPayout $model)
    {
        return $model->with(['driver', 'driver.user'])->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->setTableId('driver-payouts-table')
            ->columns($this->getColumns())
            ->minifiedAjax(route('admin.driverPayouts.driverTable'))
            ->addAction(['width' => '120px', 'printable' => false])
            ->parameters(array_merge(
                config('datatables-buttons.parameters'),
                [
                    'language' => json_decode(
                        file_get_contents(
                            base_path('resources/lang/' . app()->getLocale() . '/datatable.json')
                        ),
                        true
                    ),
                    'order' => [[3, 'desc']],
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
                'data' => 'driver.user.name',
                'title' => trans('Driver'),

            ],
            [
                'data' => 'method',
                'title' => trans('Method'),

            ],
            [
                'data' => 'amount',
                'title' => trans('Amount'),

            ],
            [
                'data' => 'date',
                'title' => trans('Date'),

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
        return 'driver_payouts_datatable_' . time();
    }
}

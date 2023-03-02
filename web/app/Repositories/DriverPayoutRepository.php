<?php

namespace App\Repositories;

use App\Models\DriverPayout;
use App\Repositories\BaseRepository;

/**
 * Class DriverPayoutRepository
 * @package App\Repositories
 * @version July 26, 2022, 12:11 pm UTC
 */

class DriverPayoutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'driver_id',
        'method',
        'amount',
        'date'
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
        return DriverPayout::class;
    }
}

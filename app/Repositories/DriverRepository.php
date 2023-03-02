<?php

namespace App\Repositories;

use App\Models\Driver;
use App\Repositories\BaseRepository;

/**
 * Class DriverRepository
 * @package App\Repositories
 * @version July 12, 2022, 12:19 pm UTC
 */

class DriverRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'active',
        'last_location_at',
        'lat',
        'lng',
        'base_price',
        'base_distance',
        'additional_distance_pricing',
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
        return Driver::class;
    }

    public function getDriversNearOf($lat, $lng, $max = 10, $limit_distance = true, $get = true)
    {
        if (setting('distance_unit', 'mi') == 'mi') {
            $multiplyFactor = '69.0';
        } else {
            $multiplyFactor = '111.1111';
        }
        $distanceQuery = "(" . $multiplyFactor . " *
                    DEGREES(ACOS(LEAST(1.0, COS(RADIANS(drivers.lat))
                    * COS(RADIANS(" . $lat . "))
                    * COS(RADIANS(drivers.lng) - RADIANS(" . $lng . "))
                    + SIN(RADIANS(drivers.lat))
                    * SIN(RADIANS(" . $lat . "))))))";

        $query = Driver::selectRaw('drivers.*,' . $distanceQuery . ' as distance,
                    (SELECT COUNT(o.id) FROM rides o
                    WHERE o.ride_status = "completed" AND
                    o.driver_id = drivers.id
                    ) as rides_count')
            ->where('drivers.active', true)
            ->with('user')
            ->whereNotNull('drivers.lat')
            ->whereNotNull('drivers.lng');
        if ($limit_distance) {
            $query->whereRaw($distanceQuery . ' < ' . setting('maximum_allowed_distance', 10));
        }

        $query->orderByRaw($distanceQuery . ' ASC')
            ->limit($max);
        if ($get) {
            return $query->get();
        } else {
            return $query;
        }
    }
}

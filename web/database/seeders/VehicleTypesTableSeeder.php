<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class VehicleTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         VehicleType::create([
             'id' => 1,
             'name' => 'Car',
             'base_price' => 5.00,
             'base_distance' => 4.00,
             'additional_distance_pricing' => 1.50,
             'app_tax' => 10.00,
             'default' => 1,
             'created_at' => Carbon::now(),
             'updated_at' => Carbon::now()
         ]);
        VehicleType::create([
            'id' => 2,
            'name' => 'Motorcycle',
            'base_price' => 2.00,
            'base_distance' => 5.00,
            'additional_distance_pricing' => 0.80,
            'app_tax' => 10.00,
            'default' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        VehicleType::create([
            'id' => 3,
            'name' => 'Luxury Car',
            'base_price' => 10.00,
            'base_distance' => 1.00,
            'additional_distance_pricing' => 2.00,
            'app_tax' => 10.00,
            'default' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        VehicleType::create([
            'id' => 4,
            'name' => 'SUV',
            'base_price' => 3.00,
            'base_distance' => 2.00,
            'additional_distance_pricing' => 2.00,
            'app_tax' => 10.00,
            'default' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}

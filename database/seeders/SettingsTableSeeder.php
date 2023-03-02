<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();

        DB::table('settings')->insert([
            array(
                'id' => 1,
                'key' => 'date_format',
                'value' => 'l jS F Y (H:i:s)',
            ),
            array(
                'id' => 2,
                'key' => 'language',
                'value' => 'en',
            ),
            array(
                'id' => 3,
                'key' => 'app_name',
                'value' => env('APP_NAME', 'Flubber'),
            ),
            array(
                'id' => 4,
                'key' => 'app_short_description',
                'value' => 'Ride a driver on demand',
            ),

        ]);
    }
}

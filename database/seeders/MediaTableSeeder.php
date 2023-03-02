<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('media')->delete();

        Media::create([
            'id' => 1,
            'model_type' => 'App\\Models\\OfflinePaymentMethod',
            'model_id' => 1,
            'uuid' => '0081ba77-4d9d-4dcb-ab99-72ce1b319742',
            'collection_name' => 'default',
            'name' => 'cash',
            'file_name' => 'cash.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 49128,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Media::create([
            'id' => 2,
            'model_type' => 'App\\Models\\VehicleType',
            'model_id' => 1,
            'uuid' => '9cb9ad51-f34c-4126-93f8-b06c2629b175',
            'collection_name' => 'default',
            'name' => 'car_icon',
            'file_name' => 'car_icon.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 85074,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        Media::create([
            'id' => 3,
            'model_type' => 'App\\Models\\VehicleType',
            'model_id' => 3,
            'uuid' => '876632d8-f4fc-4a5a-90ed-c40702b2752a',
            'collection_name' => 'default',
            'name' => 'luxury_car',
            'file_name' => 'luxury_car.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 86482,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        Media::create([
            'id' => 4,
            'model_type' => 'App\\Models\\VehicleType',
            'model_id' => 4,
            'uuid' => '784d4436-7a1b-4e79-bfa8-a6023f223f1a',
            'collection_name' => 'default',
            'name' => 'suv',
            'file_name' => 'suv.png',
            'mime_type' => 'image/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 85930,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        Media::create([
            'id' => 5,
            'model_type' => 'App\\Models\\VehicleType',
            'model_id' => 2,
            'uuid' => '4934f102-f855-402b-b4c1-868d32ab0268',
            'collection_name' => 'default',
            'name' => 'suv',
            'file_name' => 'scoot_icon.png',
            'mime_type' => 'scoot_icon/png',
            'disk' => 'public',
            'conversions_disk' => 'public',
            'size' => 55638,
            'manipulations' => '[]',
            'custom_properties' => '[]',
            'generated_conversions' => '{"icon": true}',
            'responsive_images' => '[]',
            'order_column' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}

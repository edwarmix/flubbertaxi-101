<?php

namespace Database\Seeders;

use App\Models\Permission;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

        Permission::create([
            'id' => 30,
            'name' => 'admin.dashboard',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 31,
            'name' => 'admin.dashboard.ajaxGetRides',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 32,
            'name' => 'admin.drivers.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 33,
            'name' => 'admin.drivers.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 34,
            'name' => 'admin.drivers.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 35,
            'name' => 'admin.drivers.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 36,
            'name' => 'admin.drivers.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 37,
            'name' => 'admin.settings.general',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 38,
            'name' => 'admin.settings.app',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 40,
            'name' => 'admin.settings.translations',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 41,
            'name' => 'admin.settings.social_login',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 42,
            'name' => 'admin.settings.payments_api',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 43,
            'name' => 'admin.settings.notifications',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 44,
            'name' => 'admin.settings.legal',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 45,
            'name' => 'admin.settings.currency',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 46,
            'name' => 'admin.settings.clear_cache',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 47,
            'name' => 'admin.roles.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 48,
            'name' => 'admin.roles.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 49,
            'name' => 'admin.roles.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 50,
            'name' => 'admin.roles.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 51,
            'name' => 'admin.roles.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 52,
            'name' => 'admin.roles.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 53,
            'name' => 'admin.roles.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 54,
            'name' => 'admin.offlinePaymentMethods.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 55,
            'name' => 'admin.offlinePaymentMethods.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 56,
            'name' => 'admin.offlinePaymentMethods.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 57,
            'name' => 'admin.offlinePaymentMethods.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 58,
            'name' => 'admin.offlinePaymentMethods.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 59,
            'name' => 'admin.offlinePaymentMethods.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 60,
            'name' => 'admin.offlinePaymentMethods.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 61,
            'name' => 'admin.permissions.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 62,
            'name' => 'admin.permissions.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 63,
            'name' => 'admin.settings.saveSettings',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 64,
            'name' => 'admin.users.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 65,
            'name' => 'admin.users.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 66,
            'name' => 'admin.users.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 67,
            'name' => 'admin.users.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 68,
            'name' => 'admin.users.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 69,
            'name' => 'admin.users.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 70,
            'name' => 'admin.users.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 71,
            'name' => 'admin.users.login_as',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 72,
            'name' => 'admin.rides.ajaxGetAddressesHtml',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 73,
            'name' => 'admin.rides.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 74,
            'name' => 'admin.rides.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 75,
            'name' => 'admin.rides.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 76,
            'name' => 'admin.rides.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 77,
            'name' => 'admin.rides.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 78,
            'name' => 'admin.driverPayouts.driverTable',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 79,
            'name' => 'admin.driverPayouts.driverSummary',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 80,
            'name' => 'admin.driverPayouts.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 81,
            'name' => 'admin.driverPayouts.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 82,
            'name' => 'admin.driverPayouts.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 83,
            'name' => 'admin.driverPayouts.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 84,
            'name' => 'admin.driverPayouts.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 85,
            'name' => 'admin.driverPayouts.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 86,
            'name' => 'admin.driverPayouts.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 87,
            'name' => 'admin.reports.ridesByDate',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 88,
            'name' => 'admin.reports.ridesByDriver',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 89,
            'name' => 'admin.reports.ridesByCustomer',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 90,
            'name' => 'admin.vehicle_types.index',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 91,
            'name' => 'admin.vehicle_types.create',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 92,
            'name' => 'admin.vehicle_types.store',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 93,
            'name' => 'admin.vehicle_types.show',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 94,
            'name' => 'admin.vehicle_types.edit',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 95,
            'name' => 'admin.vehicle_types.update',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 96,
            'name' => 'admin.vehicle_types.destroy',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 97,
            'name' => 'home',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 98,
            'name' => 'admin.driversJson',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        Permission::create([
            'id' => 99,
            'name' => 'admin.customersJson',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}

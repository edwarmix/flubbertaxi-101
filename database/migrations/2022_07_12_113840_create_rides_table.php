<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('vehicle_type_id');
            $table->unsignedInteger('driver_id');
            $table->string('boarding_location');
            $table->text('boarding_location_data');
            $table->boolean('save_boarding_location_for_next_ride');
            $table->text('destination_location_data');
            $table->decimal('distance', 10, 3);
            $table->decimal('driver_value', 10, 2);
            $table->decimal('app_value', 10, 2);
            $table->decimal('total_value', 10, 2);
            $table->text('customer_observation')->nullable();
            $table->unsignedInteger('offline_payment_method_id'); //0 is this is online
            $table->string('payment_gateway')->nullable();
            $table->string('gateway_id')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->dateTime('payment_status_date');
            $table->enum('ride_status', ['waiting', 'pending', 'accepted', 'in_progress', 'completed', 'cancelled'])->default('pending'); //waiting is only for online payments
            $table->dateTime('ride_status_date');
            $table->text('status_observation')->nullable();
            $table->dateTime('driver_assigned_date')->nullable();
            $table->text('assigned_drivers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rides');
    }
};

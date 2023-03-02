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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('active');
            $table->string('slug');
            $table->dateTime('last_location_at')->nullable();
            $table->decimal('lat',10,7)->nullable();
            $table->decimal('lng',10,7)->nullable();
            $table->unsignedBigInteger('vehicle_type_id');
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('plate')->nullable();
            $table->string('vehicle_document')->nullable();
            $table->string('driver_license_url')->nullable();
            $table->text('status_observation')->nullable();
            $table->enum('status',['pending','approved','reproved'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('drivers');
    }
};

<?php

namespace Database\Factories;

use App\Models\Ride;
use Illuminate\Database\Eloquent\Factories\Factory;


class RideFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ride::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'user_id' => $this->faker->numberBetween(1, 20000),
            'vehicle_type_id' => $this->faker->numberBetween(1, 3),
            'driver_id' => $this->faker->numberBetween(1, 999),
            'boarding_location' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'boarding_location_data' => '{"formatted_address":"345 Spear St, San Francisco, CA 94105, USA","geometry":{"location":{"lat":37.78968199999999,"lng":-122.3901086},"viewport":{"south":37.7883962697085,"west":-122.3916840802915,"north":37.7910942302915,"east":-122.3889861197085}},"name":"Google San Francisco","html_attributions":[],"number":"10"}',
            'save_boarding_location_for_next_ride' => $this->faker->boolean,
            'destination_location_data' => '{"formatted_address":"One Apple Park Way, Cupertino, CA 95014, USA","geometry":{"location":{"lat":37.33464379999999,"lng":-122.008972},"viewport":{"south":37.33329481970849,"west":-122.0103209802915,"north":37.33599278029149,"east":-122.0076230197085}},"name":"Apple Park","html_attributions":[],"number":"87"}',
            'distance' => $this->faker->numberBetween(0, 100),
            'driver_value' => $this->faker->numberBetween(0, 50),
            'app_value' => $this->faker->numberBetween(0, 50),
            'total_value' => $this->faker->numberBetween(50, 100),
            'customer_observation' => $this->faker->realText($this->faker->numberBetween(5, 255)),
            'offline_payment_method_id' => 1,
            'payment_gateway' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'gateway_id' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'payment_status' => 'paid',
            'payment_status_date' => $this->faker->dateTimeBetween('-1 years','now'),
            'ride_status' => 'completed',
            'ride_status_date' => $this->faker->dateTimeBetween('-1 years','now'),
            'status_observation' => '',
            'driver_assigned_date' => $this->faker->dateTimeBetween('-1 years','now'),
            'created_at' => $this->faker->dateTimeBetween('-1 years','now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 years','now')
        ];
    }
}

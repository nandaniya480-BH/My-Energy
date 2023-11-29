<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsumptionPlan>
 */
class ConsumptionPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'consumption' =>  $this->faker->numberBetween(1.00, 1000.00),
            'last_update' => now(),
            'status' => $this->faker->randomElement(['Final, Draft']),
        ];
    }
}

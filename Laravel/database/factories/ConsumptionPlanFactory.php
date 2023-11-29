<?php

namespace Database\Factories;

use App\Models\Client;
use DateInterval;
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
        $date = $this->faker->dateTimeBetween('2023-01-01', '2023-11-31');
        $futureDate = $date->add(new DateInterval("P{$this->faker->numberBetween(1, 30)}D"));
        return [
            'consumption' => $this->faker->randomFloat(2, 1.01, 1000.99),
            'status' => $this->faker->randomElement(['Final', 'Draft']),
            'last_update' => $futureDate,
            'created_at' => $this->faker->dateTimeBetween('2023-01-01', '2023-12-31')
        ];
    }
}

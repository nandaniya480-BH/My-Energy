<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientPlan>
 */
class ClientPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $short_name = ['Workweek', 'FromBattery', 'ToBattery', 'Weekend', 'Friday', 'Friday', 'Workweek'];
        $description = ['Business workdays', 'from Battery storage of the company', 'Battery storage of the company', 'Week ends and bank holidays ', 'Special schedule for Fridays', 'Special schedule for Fridays', 'Business workdays'];
        $purchase_source = ['HUPX', 'Battery', 'OKTE', 'OKTE', 'HUPX', 'HUPX', 'HUPX'];

        return [
            'short_name' => $this->faker->randomElement($short_name),
            'description' => $this->faker->randomElement($description),
            'status' => $this->faker->randomElement([0, 1]),
            'client_id' => Client::all()->random()->id,
            'purchase_source' => $this->faker->randomElement($purchase_source),
            'source_capacity' => $this->faker->numberBetween(1, 1000),
        ];
    }
}

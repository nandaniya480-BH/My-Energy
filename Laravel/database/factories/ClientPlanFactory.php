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
        $short_name = ['Workweek', 'FromBattery', 'ToBattery', 'Weekend', 'Friday', 'Workweek'];
        $description = ['Business workdays', 'from Battery storage of the company', 'Battery storage of the company', 'Week ends and bank holidays ', 'Special schedule for Fridays', 'Special schedule for Fridays', 'Business workdays'];
        $purchase_source = ['HUPX', 'Battery', 'OKTE', 'OKTE', 'HUPX', 'HUPX', 'HUPX'];

        $data = [
            [
                'short_name' => 'Workweek',
                'description' => 'Business workdays',
                'purchase_source' => 'HUPX'
            ],
            [
                'short_name' => 'FromBattery',
                'description' => 'from Battery storage of the company',
                'purchase_source' => 'Battery'
            ],
            [
                'short_name' => 'ToBattery',
                'description' => 'Battery storage of the company',
                'purchase_source' => 'OKTE'
            ],
            [
                'short_name' => 'Weekend',
                'description' => 'Week ends and bank holidays',
                'purchase_source' => 'OKTE'
            ],
            [
                'short_name' => 'Friday',
                'description' => 'Special schedule for Fridays',
                'purchase_source' => 'HUPX'
            ],
            [
                'short_name' => 'Friday',
                'description' => 'Special schedule for Fridays',
                'purchase_source' => 'HUPX'
            ],
            [
                'short_name' => 'Workweek',
                'description' => 'Business workdays',
                'purchase_source' => 'HUPX'
            ],
        ];

        $randomPlan = $this->faker->randomElement($data);

        return [
            'short_name' => $randomPlan['short_name'],
            'description' => $randomPlan['description'],
            'status' => $this->faker->randomElement([0, 1]),
            'purchase_source' => $randomPlan['purchase_source'],
            'source_capacity' => $this->faker->numberBetween(1, 1000),
        ];
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'department_id' => Department::inRandomOrder()->first()->id,
            'full_name' => $this->faker->firstName . $this->faker->lastName,
            "address" => $this->faker->address,
            "region" => "EU",
            "teams_link" => $this->faker->url,
        ];
    }
}

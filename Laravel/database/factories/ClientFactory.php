<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientPlan;
use App\Models\ClientUser;
use App\Models\ConsumptionPlan;
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
            'full_name' => $this->faker->firstName . $this->faker->lastName,
            "address" => $this->faker->address,
            "region" => "EU",
            "teams_link" => $this->faker->url,
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (Client $client) {
            ClientUser::factory()
                ->count(random_int(5, 10))
                ->create(['client_id' => $client->id]);

            ClientPlan::factory()
                ->count(random_int(5, 10))
                ->create(['client_id' => $client->id]);

            ConsumptionPlan::factory()
                ->count(random_int(50, 100))
                ->create([
                    'client' => $client->full_name,
                    'client_user' => $client->users->random()->full_name,
                    'client_plan' => $client->plans->random()->short_name,
                ]);
        });
    }
}

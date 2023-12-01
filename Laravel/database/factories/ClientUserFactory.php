<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClientUser>
 */
class ClientUserFactory extends Factory
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
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
        ];
    }
    public function configure()
    {
        return $this->afterCreating(function (ClientUser $client_user) {
            User::factory()->create([
                'user_name' => $client_user->full_name,
                'role_id' => $client_user->role_id
            ]);
        });
    }
}

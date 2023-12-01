<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Role::truncate();
        $this->call([
            RoleSeeder::class
        ]);

        User::truncate();
        User::factory()->create([
            'user_name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => Role::where('name', 'admin')->first()->id 
        ]);

        $this->call([
            ClientSeeder::class,
            ConsumptionPlanSeeder::class
        ]);
    }
}

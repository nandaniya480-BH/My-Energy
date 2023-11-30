<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
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
            'role_id' => Role::where('name', 'admin')->first()->id ?? 1
        ]);

        $this->call([
            ClientSeeder::class,
            ClientUserSeeder::class
        ]);
    }
}

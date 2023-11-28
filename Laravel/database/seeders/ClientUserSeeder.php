<?php

namespace Database\Seeders;

use App\Models\ClientUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientUser::truncate();
        ClientUser::factory(10)->create();
    }
}

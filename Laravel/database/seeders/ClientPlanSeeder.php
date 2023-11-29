<?php

namespace Database\Seeders;

use App\Models\ClientPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientPlan::truncate();
        ClientPlan::factory(10)->create();
    }
}
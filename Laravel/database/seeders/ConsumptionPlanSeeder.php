<?php

namespace Database\Seeders;

use App\Models\ConsumptionPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConsumptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConsumptionPlan::truncate();
        ConsumptionPlan::factory(10)->create();
    }
}

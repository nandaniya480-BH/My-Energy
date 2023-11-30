<?php

namespace Database\Seeders;

use App\Models\Client;
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

        $clients = Client::with('plans', 'users')->get();
        foreach ($clients as $client) {
            foreach ($client['plans'] as $plan) {
                ConsumptionPlan::factory()
                    ->count(100)
                    ->create([
                        'client' => $client->full_name,
                        'client_user' => $client->users->random()->full_name,
                        'client_plan' => $plan->short_name,
                    ]);
            }
        }
    }
}

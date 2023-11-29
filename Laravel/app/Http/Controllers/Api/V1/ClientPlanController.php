<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\ClientPlan;

class ClientPlanController extends Controller
{
    use ApiResponseTrait;

    public function get_client_plans($client_id)
    {
        try {
            $client_plan = ClientPlan::where('client_id', $client_id)->get();
            if (count($client_plan) > 0) {
                return $this->success('Client Plans', $client_plan);
            } else {
                throw new \Exception('No record found.', 404);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }
}

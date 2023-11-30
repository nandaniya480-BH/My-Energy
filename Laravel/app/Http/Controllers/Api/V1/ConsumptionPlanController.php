<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\ConsumptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ConsumptionPlanController extends Controller
{

    use ApiResponseTrait;

    public function client_user_index($client, Request $request)
    {
        try {
            $data = ConsumptionPlan::query()
                ->whereClient($client)
                // ->with('plans', 'users')
                ->orderBy($request->sortField ?? 'id', $request->sortOrder ?? 'asc')
                ->get();

            return $this->success(
                'Consumption Plan list',
                [
                    'data' => $data,
                ],
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function consumption_plan_data($client, Request $request)
    {
        try {
            $fromDate = $request->input('from_date');
            $toDate = $request->input('to_date');

            $start_date = $fromDate ? Carbon::parse($fromDate)->startOfDay() : null;
            $end_date = $toDate ? Carbon::parse($toDate)->endOfDay() : null;

            $query = ConsumptionPlan::whereClient($client)
                ->with('client_plan', 'client_user', 'client')
                ->when($start_date, function ($query) use ($start_date) {
                    $query->where('created_at', '>=', $start_date);
                })
                ->when($end_date, function ($query) use ($end_date) {
                    $query->where('created_at', '<=', $end_date);
                })
                ->get();

            return $this->success(
                'Consumption Plan list',
                ['data' => $query]
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client' => [
                    'required',
                    // 'exists:clients,full_name'
                ],
                'client_user' => [
                    'nullable',
                    // 'exists:client_users,full_name'
                ],
                'client_plan' => [
                    'nullable',
                    // 'exists:client_plans,short_name'
                ],
                'consumption' => ['required', 'numeric'],
                'status' => ['required'],
            ]);

            if ($validator->fails()) {
                return $this->error(
                    'The given data was invalid.',
                    $validator->errors(),
                    422
                );
            }
            $input = $request->all();

            DB::beginTransaction();
            $data = ConsumptionPlan::create($input);
            DB::commit();
            return $this->success('New Consumption Plan created successfully.', $data,  Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), null,  $e->getCode());
        }
    }


    public function show($id)
    {
        try {
            $consumption_plan = ConsumptionPlan::query()
                // ->with('plan', 'user')
                ->find($id);
            if ($consumption_plan) {
                return $this->success('Consumption Plan info', $consumption_plan);
            } else {
                throw new \Exception('No record found.', 404);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'client' => [
                    'required',
                    // 'exists:clients,full_name'
                ],
                'client_user' => [
                    'nullable',
                    // 'exists:client_users,full_name'
                ],
                'client_plan' => [
                    'nullable',
                    // 'exists:client_plans,short_name'
                ],
                'consumption' => ['required', 'numeric'],
                'status' => ['required'],
            ]);

            if ($validator->fails()) {
                return $this->error(
                    'The given data was invalid.',
                    $validator->errors(),
                    422
                );
            }
            $input = $request->all();

            $consumption_plan = ConsumptionPlan::find($id);

            DB::beginTransaction();
            if ($consumption_plan) {
                $consumption_plan->update($input);
                DB::commit();
                return $this->success('Consumption Plan updated successfully.');
            } else {
                throw new \Exception('No record found.', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), null,  $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $consumption_plan = ConsumptionPlan::find($id);
            if ($consumption_plan) {
                DB::beginTransaction();
                $consumption_plan->delete();
                DB::commit();
                return $this->success('Consumption Plan deleted successfully.');
            } else {
                throw new \Exception('No record found.', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), null,  $e->getCode());
        }
    }
}

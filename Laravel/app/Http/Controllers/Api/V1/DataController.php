<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Client;
use App\Models\ClientPlan;
use App\Models\ClientUser;
use App\Models\Role;
use Illuminate\Http\Request;

class DataController extends Controller
{
    use ApiResponseTrait;

    public function client()
    {
        try {
            return $this->success(
                'Client list',
                Client::query()
                    ->get()
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function role($id = null)
    {
        try {
            return $this->success(
                'Role',
                Role::query()
                    ->when($id && $id != "", function ($query) use ($id) {
                        return $query->where('id', $id);
                    })
                    ->with('permissions')
                    ->get()
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function clientPlan(Request $request)
    {

        try {
            return $this->success(
                'Client User list',
                ClientUser::query()
                    ->when($request->has('client_id') && $request->client_id != "", function ($query) use ($request) {
                        return $query->where('client_id', $request->client_id);
                    })
                    ->get()
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function clientUser(Request $request)
    {
        try {
            return $this->success(
                'Client Plan list',
                ClientPlan::query()
                    ->when($request->has('client_id') && $request->client_id != "", function ($query) use ($request) {
                        return $query->where('client_id', $request->client_id);
                    })
                    ->get()
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }
}

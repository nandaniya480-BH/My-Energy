<?php

namespace App\Http\Controllers\Api\V1;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Address;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {
            $data = Client::query()
                // ->whereStatus($request->status)
                ->when($request->has('full_name') && $request->full_name[0] != "", function ($query) use ($request) {
                    $query->where('full_name', "LIKE", "%" . $request->full_name[0] . "%");
                })
                ->when($request->has('address') && $request->address[0] != "", function ($query) use ($request) {
                    $query->where('address', "LIKE", "%" . $request->address[0] . "%");
                })
                ->when($request->has('region') && $request->region[0] != "", function ($query) use ($request) {
                    $query->where('region', "LIKE", "%" . $request->region[0] . "%");
                })
                ->when($request->has('teams_link') && $request->teams_link[0] != "", function ($query) use ($request) {
                    $query->where('teams_link', "LIKE", "%" . $request->teams_link[0] . "%");
                })
                ->orderBy($request->sortField ?? 'id', $request->sortOrder ?? 'asc')
                ->get();

            return $this->success(
                'Client list',
                [
                    'data' => $data,
                ]
            );
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => ['required'],
                'address' => ['required'],
                'region' => ['required'],
                'teams_link' => ['required', 'url'],
            ]);

            if ($validator->fails()) {
                return $this->error(
                    'The given data was invalid.',
                    $validator->errors(),
                    422
                );
            }

            Client::create($request->only([
                'full_name',
                'address',
                'region',
                'teams_link',
            ]));

            return $this->success('New Client created successfully.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null,  $e->getCode());
        }
    }

    public function show($id)
    {
        try {
            $client = Client::find($id);

            if ($client) {
                return $this->success('Client info', $client);
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
                'full_name' => ['required'],
                'address' => ['required'],
                'region' => ['required'],
                'teams_link' => ['required', 'url'],
            ]);

            if ($validator->fails()) {
                return $this->error(
                    'The given data was invalid.',
                    $validator->errors(),
                    422
                );
            }

            $client = Client::find($id);

            if ($client) {
                $client->update($request->only([
                    'full_name',
                    'address',
                    'region',
                    'teams_link',
                ]));
                return $this->success('Client updated successfully.');
            } else {
                throw new \Exception('No record found.', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null,  $e->getCode());
        }
    }

    public function destroy($id)
    {
        try {
            $client = Client::find($id);
            if ($client) {
                $client->delete();
                return $this->success('Client deleted successfully.');
            } else {
                throw new \Exception('No record found.', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), null,  $e->getCode());
        }
    }
}

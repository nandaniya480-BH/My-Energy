<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'permissions' => [
                    [
                        'module' => 'client',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'client',
                'permissions' => [
                    [
                        'module' => 'client_user',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'consumption_plan',
                'permissions' => [
                    [
                        'module' => 'consumption_plan',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ]
        ];

        foreach ($roles as $role) {
            $NewRole = Role::create(['name' => $role['name']]);
            foreach ($role['permissions'] as $permission) {
                Permission::create([
                    'role_id' => $NewRole->id,
                    'module' => $permission['module'],
                    'access' => json_encode($permission['permission'])
                ]);
            }
        }
    }
}

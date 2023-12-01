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
                'name' => 'Admin',
                'permissions' => [
                    [
                        'module' => 'Client',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'Client',
                'permissions' => [
                    [
                        'module' => 'Client User',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'Consumption Plan',
                'permissions' => [
                    [
                        'module' => 'Consumption Plan',
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

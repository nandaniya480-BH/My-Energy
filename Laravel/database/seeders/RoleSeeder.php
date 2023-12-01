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
                    ],
                    [
                        'module' => 'Client User',
                        'permission' => ['C', 'R', 'U', 'D']
                    ],
                    [
                        'module' => 'Consumption Plan',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'Manager',
                'permissions' => [
                    [
                        'module' => 'Client',
                        'permission' => []
                    ],
                    [
                        'module' => 'Client User',
                        'permission' => ['C', 'R', 'U', 'D']
                    ],
                    [
                        'module' => 'Consumption Plan',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'Employee',
                'permissions' => [
                    [
                        'module' => 'Client',
                        'permission' => []
                    ],
                    [
                        'module' => 'Client User',
                        'permission' => []
                    ],
                    [
                        'module' => 'Consumption Plan',
                        'permission' => ['C', 'R', 'U', 'D']
                    ]
                ]
            ],
            [
                'name' => 'Guest',
                'permissions' => [
                    [
                        'module' => 'Client',
                        'permission' => ['R']
                    ],
                    [
                        'module' => 'Client User',
                        'permission' => ['R']
                    ],
                    [
                        'module' => 'Consumption Plan',
                        'permission' => ['R']
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

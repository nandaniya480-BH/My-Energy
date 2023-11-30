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
            'admin',
            'client'
        ];
        $modules = [
            'client_user',
            'consumption_plan'
        ];
        foreach ($roles as $role) {
            $NewRole = Role::create(['name' => $role]);
            foreach ($modules as $modal) {
                Permission::create([
                    'role_id' => $NewRole->id,
                    'module' => $modal,
                    'access' => json_encode(['C', 'R', 'U', 'D'])
                ]);
            }
        }
    }
}

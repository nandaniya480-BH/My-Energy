<?php

namespace Database\Seeders;

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
        foreach ($roles as $role) {
            $NewRole = Role::create(['name' => $role]);
        }
    }
}

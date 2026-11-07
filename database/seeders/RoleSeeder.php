<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'Admin Farming'],
            ['id' => 2, 'name' => 'Karyawan Farm'],
            ['id' => 3, 'name' => 'Manager'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['id' => $role['id']],
                [
                    'name'       => $role['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
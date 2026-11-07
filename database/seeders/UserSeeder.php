<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin Farming
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name'     => 'Admin Farming',
                'password' => Hash::make('12345678'),
                'status'   => 'approved',
                'role_id'  => 1,
            ]
        );

        // 2. Akun Karyawan Farm
        User::updateOrCreate(
            ['email' => 'karyawan@gmail.com'],
            [
                'name'     => 'Karyawan Farm',
                'password' => Hash::make('12345678'),
                'status'   => 'approved',
                'role_id'  => 2,
            ]
        );

        // 3. Akun Manager
        User::updateOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'name'     => 'Manager Farm',
                'password' => Hash::make('12345678'),
                'status'   => 'approved',
                'role_id'  => 3,
            ]
        );
    }
}
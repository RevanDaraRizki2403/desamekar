<?php

namespace Database\Seeders;

use App\Models\Resident;
Use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'id' =>1,
            'name'=> 'Admin',
            'email'=> 'admin@gmail.com',
            'password'=> 'password',
            'status'=> 'approved',
            'role_id'=> '1',

        ]);

         User::create([
            'id' =>2,
            'name'=> 'Penduduk1',
            'email'=> 'penduduk@gmail.com',
            'password'=> 'password',
            'status'=> 'approved',
            'role_id'=> '2',

        ]);

        Resident::create([
            'user_id' => 2,
            'nik' => '1234567891234567',
            'name' => 'Adam',
            'gender' => 'male',
            'status' => 'active',
        ]);
            }

}

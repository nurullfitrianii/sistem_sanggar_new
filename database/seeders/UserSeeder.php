<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'admin_ketua',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'Ketua',
                'status' => 'Aktif',
            ],
            [
                'username' => 'humas_admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'Humas',
                'status' => 'Aktif',
            ],
            [
                'username' => 'bendahara_admin',
                'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                'role' => 'Bendahara',
                'status' => 'Aktif',
            ],
        ];

        \App\Models\User::insert($users);
    }
}

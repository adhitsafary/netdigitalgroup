<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'teknisi',
                'email' => 'teknisi@gmail.com',
                'role' => 'teknisi',
                'password' => bcrypt('123456')
            ],
            [
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => bcrypt('123456')
            ],
            [
                'name' => 'superadmin',
                'email' => 'superadmin@gmail.com',
                'role' => 'superadmin',
                'password' => bcrypt('123456')
            ],
        ];

        foreach ($userData as $key => $val) {
                User::create($val);
        }
    }
}

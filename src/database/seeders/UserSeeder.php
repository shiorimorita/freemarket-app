<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'id' => 1,
                'name' => 'テストユーザー01',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'テストユーザー02',
                'email' => 'test02@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        User::insert($users);
    }
}

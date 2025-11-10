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
        $users =
            [
                [
                    'name' => 'テストユーザー01',
                    'email' => 'test@example.com',
                    'password' => Hash::make('password'),
                ],
                [
                    'name' => 'テストユーザー02',
                    'email' => 'test02@example.com',
                    'password' => Hash::make('password'),
                ],
            ];

        User::insert($users);
    }
}

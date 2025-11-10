<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profiles = [
            [
                'user_id' => 1,
                'post_code' => '111-1111',
                'address' => 'address01',
            ],
            [
                'user_id' => 2,
                'post_code' => '222-2222',
                'address' => 'address02',
            ],
        ];

        Profile::insert($profiles);
    }
}

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sold;
use App\Models\User;
use App\Models\Item;

class SoldFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */

    protected $model = Sold::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'item_id' => Item::factory(),
            'method' => 'コンビニ払い',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-1-1',
            'building' => 'null',
        ];
    }
}

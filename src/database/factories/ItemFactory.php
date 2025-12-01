<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $num = $this->faker->unique()->numberBetween(1, 9999);

        return [
            'name' => "FACTORY_ITEM_{$num}",
            'price' => $this->faker->numberBetween(300, 50000),
            'brand' => "FACTORY_BRAND_{$num}",
            'description' => "FACTORY_DESC_{$num}",
            'image_path' => "images/product_{$num}.jpg",
            'condition' => '良好',
            'user_id' => User::factory(),
        ];
    }
}

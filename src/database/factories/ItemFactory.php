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
        return [
            'name' => $this->faker->word(),
            'price' => $this->faker->numberBetween(300, 50000),
            'brand' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'image_path' => 'images/default.jpg',
            'condition' => '良好',
            'user_id' => User::factory(),
        ];
    }
}

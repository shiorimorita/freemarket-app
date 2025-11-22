<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement([
                'ファッション',
                '家電',
                'インテリア',
                'レディース',
                'メンズ',
                'コスメ',
                '本',
                'ゲーム',
                'スポーツ',
                'キッチン',
                'ハンドメイド',
                'アクセサリー',
                'おもちゃ',
                'ベビー・キッズ'
            ]),
        ];
    }
}

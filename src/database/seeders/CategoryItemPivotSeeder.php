<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Item;

class CategoryItemPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mapping = [
            1 => [1, 5],   // Item ID 1 → カテゴリ 1,5
            2 => [2],      // Item ID 2 → 2
            3 => [10],     // Item ID 3 → 10
            4 => [1, 5],
            5 => [2],
            6 => [2],
            7 => [1, 4],
            8 => [10],
            9 => [2, 10],
            10 => [4, 6],
        ];

        foreach ($mapping as $itemId => $categoryIds) {
            $item = Item::find($itemId);

            if ($item) {
                $item->categories()->attach($categoryIds);
            }
        }
    }
}

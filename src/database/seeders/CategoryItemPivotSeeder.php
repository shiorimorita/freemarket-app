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
        Item::find(1)->categories()->attach([1, 5]);
        Item::find(2)->categories()->attach([2]);
        Item::find(3)->categories()->attach([10]);
        Item::find(4)->categories()->attach([1, 5]);
        Item::find(5)->categories()->attach([2]);
        Item::find(6)->categories()->attach([2]);
        Item::find(7)->categories()->attach([1, 4]);
        Item::find(8)->categories()->attach([10]);
        Item::find(9)->categories()->attach([2, 10]);
        Item::find(10)->categories()->attach([4, 6]);
    }
}

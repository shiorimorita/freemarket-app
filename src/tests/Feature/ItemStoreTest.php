<?php

namespace Tests\Feature;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use App\Models\Item;

class ItemStoreTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 商品出品画面にて必要な情報が保存できること（カテゴリ、商品の状態、商品名、ブランド名、商品の説明、販売価格） */
    public function test_example()
    {
        $user = User::factory()->withProfile()->create([
            'email_verified_at' => now()
        ]);
        $this->actingAs($user);

        $this->get('/sell');

        $categories = Category::factory()->count(2)->create();

        $file = UploadedFile::fake()->create('test.jpg');

        $this->post('/sell', ['image_path' => $file, 'name' => 'カメラ', 'brand' => '東芝', 'description' => '目立った傷もない美品です。', 'price' => '10000', 'condition' => '良好', 'category_ids' => $categories->pluck('id')->toArray(),]);

        $item = Item::first();

        foreach ($categories as $category) {
            // pivot 確認
            $this->assertDatabaseHas('category_item_pivot', [
                'item_id'     => $item->id,
                'category_id' => $category->id,
            ]);
        }

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'カメラ',
            'brand' => '東芝',
            'description' => '目立った傷もない美品です。',
            'price' => 10000,
            'condition' => '良好',
        ]);
    }
}

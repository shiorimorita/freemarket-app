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
    public function test_sell_item_confirm()
    {
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $this->get('/sell')->assertStatus(200);

        $data = [
            'name' => 'カメラ',
            'brand' => '東芝',
            'description' => '目立った傷もない美品です。',
            'price' => '10000',
            'condition' => '良好'
        ];

        $data['categories'] = Category::factory()->count(2)->create();
        $data['file'] = UploadedFile::fake()->create('test.jpg');

        $response = $this->post('/sell', [
            'image_path' => $data['file'],
            'name' => $data['name'],
            'brand' => $data['brand'],
            'description' => $data['description'],
            'price' => $data['price'],
            'condition' => $data['condition'],
            'category_ids' => $data['categories']->pluck('id')->toArray(),
        ]);

        $response->assertRedirect('/')->assertStatus(302);

        $item = Item::first();

        foreach ($data['categories'] as $category) {
            $this->assertDatabaseHas('category_item_pivot', [
                'item_id'     => $item->id,
                'category_id' => $category->id,
            ]);
        }

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => $data['name'],
            'brand' => $data['brand'],
            'description' => $data['description'],
            'price' => $data['price'],
            'condition' => $data['condition'],
        ]);
    }
}

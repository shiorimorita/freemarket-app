<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 「購入する」ボタンを押下すると購入が完了する */
    public function test_purchase_complete()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->withProfile()->create();
        $this->actingAs($buyer);

        $this->get("/purchase/{$item->id}")->assertStatus(200);

        //配送先・支払い方法をセッションに保存し、post
        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室'
        ]);

        $this->post("/purchase/method/{$item->id}", [
            'method' => 'コンビニ払い'
        ]);

        $this->post("/purchase/{$item->id}");

        // Sold登録確認
        $this->assertDatabaseHas('solds', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);
    }

    /* 購入した商品は商品一覧画面にて「sold」と表示される */
    public function test_purchase_sold_badge()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);
        $response = $this->get('/')->assertStatus(200);
        $response->assertDontSee('<span class="sold-badge--list sold-badge">Sold</span>', false);

        $this->get("/purchase/{$item->id}")->assertStatus(200);

        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室'
        ]);

        $this->post("/purchase/method/{$item->id}", [
            'method' => 'コンビニ払い'
        ]);

        $this->post("/purchase/{$item->id}");

        $response = $this->get('/')->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
        $response->assertSee('<span class="sold-badge--list sold-badge">Sold</span>', false);
    }

    /* 「プロフィール/購入した商品一覧」に追加されている */
    public function test_purchase_item_confirm()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->withProfile()->create();
        $this->actingAs($buyer);

        $response = $this->get('/mypage?page=buy')->assertStatus(200);
        $response->assertDontSee($item->name);
        $response->assertDontSee($item->image_path);
        $response->assertDontSee('<span class="sold-badge sold-badge--mypage">Sold</span>', false);

        $this->get("/purchase/{$item->id}")->assertStatus(200);

        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室'
        ]);

        $this->post("/purchase/method/{$item->id}", [
            'method' => 'コンビニ払い'
        ]);

        $this->post("/purchase/{$item->id}");

        $response = $this->get('/mypage?page=buy')->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
        $response->assertSee('<span class="sold-badge sold-badge--mypage">Sold</span>', false);
    }
}

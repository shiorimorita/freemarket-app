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

        //配送先をセッションに保存
        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室'
        ]);

        // 支払い方法をセッションに保存
        $this->post("/purchase/method/{$item->id}", [
            'method' => 'コンビニ払い'
        ]);

        // 購入実行
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
        $response = $this->get('/');
        $response->assertDontSee('Sold');

        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室'
        ]);

        $this->post("/purchase/method/{$item->id}", [
            'method' => 'コンビニ払い'
        ]);

        $this->post("/purchase/{$item->id}");

        $response = $this->get('/');
        $response->assertSee('Sold');
    }

    /* 「プロフィール/購入した商品一覧」に追加されている */
    public function test_purchase_item_confirm()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id, 'name' => 'TEST_0001']);

        $buyer = User::factory()->withProfile()->create();
        $this->actingAs($buyer);

        $response = $this->get('/mypage?page=buy');
        $response->assertDontSee($item->name);

        /* セッションに保存 */
        $this->post("/purchase/address/{$item->id}", [
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室'
        ]);
        $this->post("/purchase/method/{$item->id}", [
            'method' => 'コンビニ払い'
        ]);

        $this->post("/purchase/{$item->id}");

        $response = $this->get('/mypage?page=buy');
        $response->assertSee('TEST_0001');
    }
}

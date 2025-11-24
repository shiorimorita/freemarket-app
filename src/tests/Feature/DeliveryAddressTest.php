<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;

class DeliveryAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 送付先住所変更画面にて登録した住所が商品購入画面に反映されている */
    public function test_delivery_address_confirm()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        /** @var \App\Models\User $buyer */
        $buyer = User::factory()
            ->has(Profile::factory()->state([
                'post_code' => '111-1111',
                'address'   => '東京都港区1-1-1',
                'building'  => 'テストB202号室',
            ]))
            ->create();
        $this->actingAs($buyer);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('111-1111');
        $response->assertSee('東京都港区1-1-1');
        $response->assertSee('テストB202号室');

        $this->post("/purchase/address/{$item->id}", ['post_code' => '123-4567', 'address' => '東京都渋谷区1-2-3', 'building' => 'テストA101号室']);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区1-2-3');
        $response->assertSee('テストA101号室');
    }

    /* 購入した商品に送付先住所が紐づいて登録される */
    public function test_delivery_address_sold_confirm()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->withProfile()->create();
        $this->actingAs($buyer);

        /* 配送先、支払方法を保存 */
        $this->post("/purchase/address/{$item->id}", ['post_code' => '123-4567', 'address' => '東京都渋谷区1-2-3', 'building' => 'テストA101号室']);
        $this->post("/purchase/method/{$item->id}", ['method' => 'コンビニ払い']);

        /* 商品を購入する */
        $this->post("/purchase/{$item->id}");

        /* Sold テーブルに対象商品と住所が紐づいている */
        $this->assertDatabaseHas('solds', [
            'item_id' => $item->id,
            'user_id' => $buyer->id,
            'method' => 'コンビニ払い',
            'post_code' => '123-4567',
            'address' => '東京都渋谷区1-2-3',
            'building' => 'テストA101号室',
        ]);
    }
}

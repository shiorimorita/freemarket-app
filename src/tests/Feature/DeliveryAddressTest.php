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

        $delivery = [
            'post_code' => '111-1111',
            'address'   => '東京都港区1-1-1',
            'building'  => 'テストB202号室',
        ];

        /** @var \App\Models\User $buyer */
        $buyer = User::factory()->has(Profile::factory()->state($delivery))->create();
        $this->actingAs($buyer);

        /* デフォルトのプロフィール住所が設定されているか確認 */
        $response = $this->get("/purchase/{$item->id}")->assertStatus(200);
        $response->assertSee($delivery['post_code']);
        $response->assertSee($delivery['address']);
        $response->assertSee($delivery['building']);

        $deliveryUpdate = ['post_code' => '123-4567', 'address' => '東京都渋谷区1-2-3', 'building' => 'テストA101号室'];

        /* 送付先住所変更を変更 */
        $this->post("/purchase/address/{$item->id}", $deliveryUpdate)->assertRedirect("/purchase/{$item->id}");
        $response = $this->get("/purchase/{$item->id}")->assertStatus(200);

        $response->assertSee($deliveryUpdate['post_code']);
        $response->assertSee($deliveryUpdate['address']);
        $response->assertSee($deliveryUpdate['building']);
    }

    /* 購入した商品に送付先住所が紐づいて登録される */
    public function test_delivery_address_sold_confirm()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->withProfile()->create();
        $this->actingAs($buyer);

        $delivery = ['post_code' => '123-4567', 'address' => '東京都渋谷区1-2-3', 'building' => 'テストA101号室'];
        $method = ['method' => 'コンビニ払い'];

        /* 配送先、支払方法を保存 */
        $this->post("/purchase/address/{$item->id}", $delivery);
        $this->post("/purchase/method/{$item->id}", $method);

        /* 商品を購入する */
        $this->post("/purchase/{$item->id}");

        /* Sold テーブルに対象商品と住所が紐づいている */
        $this->assertDatabaseHas('solds', [
            'item_id' => $item->id,
            'user_id' => $buyer->id,
            'method' => $method['method'],
            'post_code' => $delivery['post_code'],
            'address' => $delivery['address'],
            'building' => $delivery['building'],
        ]);
    }
}

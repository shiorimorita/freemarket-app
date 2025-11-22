<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Sold;
use App\Models\User;

class ItemIndexTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 全商品を取得できる */
    public function test_item_index_displays_all_items()
    {
        $items = Item::factory()->count(3)->create();
        $response = $this->get('/');
        $response->assertStatus(200);

        foreach ($items as $item) {
            $response->assertSee($item->name);
            $response->assertSee($item->image_path);
        }
    }

    /* 購入済み商品は「Sold」と表示される*/
    public function test_item_index_purchase_items()
    {
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $buyer = User::factory()->create();

        $response = $this->get('/');
        $response->assertDontSee('Sold');

        Sold::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee($item->name);

        $this->assertTrue($item->fresh()->is_sold);
        $response->assertSeeInOrder(['Sold', $item->name]);
    }

    /* 未購入の商品は Sold が表示されない */
    public function test_item_index_not_purchased_items_do_not_show_sold()
    {
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee($item->name);
        $this->assertFalse($item->fresh()->is_sold);
        $response->assertDontSee('Sold');
    }

    /* 自分が出品した商品は表示されない */
    public function test_item_sell()
    {
        $seller = User::factory()->withProfile()->create();
        $myItem = Item::factory()->create([
            'user_id' => $seller->id,
        ]);
        $this->actingAs($seller);
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee($myItem->name);
    }
}

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
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->withProfile()->create();

        $response = $this->get('/')->assertStatus(200);
        $response->assertDontSee('<span class="sold-badge--list sold-badge">Sold</span>', false);

        Sold::factory()->create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get('/')->assertStatus(200);
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
        $response->assertSee('<span class="sold-badge--list sold-badge">Sold</span>', false);
    }

    /* 自分が出品した商品は表示されない */
    public function test_item_sell()
    {
        $seller = User::factory()->withProfile()->create();
        $myItem = Item::factory()->create(['user_id' => $seller->id,]);

        $response = $this->get('/')->assertStatus(200);
        $response->assertSee($myItem->name);
        $response->assertSee($myItem->image_path);

        $this->actingAs($seller);
        $response = $this->get('/')->assertStatus(200);
        $response->assertDontSee($myItem->name);
        $response->assertDontSee($myItem->image_path);
    }
}

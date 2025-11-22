<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Item;
use App\Models\Like;
use App\Models\Sold;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* いいねした商品だけが表示される*/
    public function test_likes_only_view()
    {
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $likeItem = Item::factory()->create([
            'name' => 'LIKE_TEST_ITEM_001',
        ]);
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likeItem->id,
        ]);

        $notLikeItem = Item::factory()->create([
            'name' => 'NOT_LIKED_TEST_ITEM_001',
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSee($likeItem->name);
        $response->assertDontSee($notLikeItem);
    }

    /* 購入済み商品は「Sold」と表示される */
    public function test_like_item_purchase()
    {
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $item = Item::factory()->create(['name' => 'TEST_MYLIST_SOLD_ITEM_001']);
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item->id]);
        Sold::factory()->create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertSee($item->name);
        $response->assertSee('Sold');
    }

    /* 未認証の場合は何も表示されない */
    public function test_mylist_shows_no_items_for_guest()
    {
        $item = Item::factory()->create([
            'name' => 'TEST_ITEM_001'
        ]);

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);

        $response->assertDontSee($item->name);
    }
}

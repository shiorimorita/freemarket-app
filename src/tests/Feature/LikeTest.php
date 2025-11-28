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

        $likeItem = Item::factory()->create();
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likeItem->id,
        ]);

        $notLikeItem = Item::factory()->create();

        $response = $this->get('/?tab=mylist')->assertStatus(200);

        $response->assertSee($likeItem->name);
        $response->assertSee($likeItem->image_path);
        $response->assertDontSee($notLikeItem->name);
        $response->assertDontSee($notLikeItem->image_path);
    }

    /* 購入済み商品は「Sold」と表示される */
    public function test_like_item_purchase()
    {
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $item = Item::factory()->create();
        Like::factory()->create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $response->assertDontSee('<span class="sold-badge--list sold-badge">Sold</span>', false);

        Sold::factory()->create(['user_id' => $user->id, 'item_id' => $item->id]);

        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $response->assertSee($item->image_path, false);
        $response->assertSee('<span class="sold-badge--list sold-badge">Sold</span>', false);
        $response->assertSee($item->name, false);
    }

    /* 未認証の場合は何も表示されない */
    public function test_mylist_shows_no_items_for_guest()
    {
        $items = Item::factory()->count(3)->create();
        $response = $this->get('/?tab=mylist')->assertStatus(200);

        foreach ($items as $item) {
            $response->assertDontSee($item->name);
            $response->assertDontSee($item->image_path);
        }
    }
}

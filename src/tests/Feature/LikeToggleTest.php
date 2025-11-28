<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class LikeToggleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* いいねアイコンを押下することによって、いいねした商品として登録することができる。 */
    public function test_like_register()
    {
        $item = Item::factory()->create();
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $response = $this->get("item/{$item->id}")->assertStatus(200);
        $response->assertSee('<span class="like__count">0</span>', false);

        $response = $this->post("item/{$item->id}/like");

        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get("item/{$item->id}")->assertStatus(200);
        $response->assertSee('<span class="like__count">1</span>', false);
    }

    /* 追加済みのアイコンは色が変化する */
    public function test_like_color_change()
    {
        $item = Item::factory()->create();

        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $response = $this->get("item/{$item->id}")->assertStatus(200);
        $response->assertDontSee('is-liked', false);

        $this->post("item/{$item->id}/like");
        $response = $this->get("item/{$item->id}")->assertStatus(200);
        $response->assertSee('is-liked', false);
    }

    /* 再度いいねアイコンを押下することによって、いいねを解除することができる。 */
    public function test_like_delete()
    {
        $item = Item::factory()->create();
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $response = $this->get("item/{$item->id}")->assertStatus(200);
        $response->assertSee('<span class="like__count">0</span>', false);

        /* いいねをする */
        $this->post("item/{$item->id}/like");
        $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'item_id' => $item->id]);
        $response = $this->get("item/{$item->id}");
        $response->assertSee('<span class="like__count">1</span>', false);
        $response->assertSee('is-liked', false);

        /* いいねを解除 */
        $this->post("item/{$item->id}/like");
        $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'item_id' => $item->id]);
        $response = $this->get("item/{$item->id}");
        $response->assertSee('<span class="like__count">0</span>', false);
        $response->assertDontSee('is-liked', false);
    }
}

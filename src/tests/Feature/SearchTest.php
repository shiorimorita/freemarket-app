<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 「商品名」で部分一致検索ができる */
    public function test_search_keyword()
    {
        $keywordHitItem1 = Item::factory()->create(['name' =>  'Apple Watch']);
        $keywordHitItem2 = Item::factory()->create(['name' => 'Pineapple Case']);
        $keywordNotHitItem = Item::factory()->create(['name' => 'Galaxy Phone']);

        $keyword = 'Apple';

        $response = $this->get("/?keyword={$keyword}&tab=recommend")->assertStatus(200);
        $response->assertDontSee($keywordNotHitItem->name);
        $response->assertSee($keywordHitItem1->name);
        $response->assertSee($keywordHitItem1->image_path);
        $response->assertSee($keywordHitItem2->name);
        $response->assertSee($keywordHitItem2->image_path);
    }

    /* 検索状態がマイリストでも保持されている */
    public function test_search_keyword_keep_mylist()
    {
        $likedItem = Item::factory()->create(['name' => 'Apple Watch']);
        $notLikedItemSearchHit = Item::factory()->create(['name' => 'Pineapple Case']);
        $notLikedItem = Item::factory()->create(['name' => 'Galaxy Phone']);

        $user = User::factory()->withProfile()->create();
        Like::factory()->create(['item_id' => $likedItem->id, 'user_id' => $user->id]);

        $keyword = 'Apple';

        $this->actingAs($user);
        $response = $this->get("/?keyword={$keyword}&tab=recommend")->assertStatus(200);

        $response->assertSee($likedItem->name);
        $response->assertSee($notLikedItemSearchHit->name);
        $response->assertDontSee($notLikedItem->name);

        $response = $this->get('/?tab=mylist')->assertStatus(200);
        $this->assertEquals($keyword, session('search.keyword'));

        $response->assertSee($likedItem->name);
        $response->assertSee($likedItem->image_path);
        $response->assertDontSee($notLikedItemSearchHit->name);
        $response->assertDontSee($notLikedItem->name);
    }
}

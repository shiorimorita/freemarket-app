<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
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
        Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Galaxy Phone']);
        Item::factory()->create(['name' => 'Pineapple Case']);

        $response = $this->get('/?keyword=Apple');
        $response->assertStatus(200);
        $response->assertSee('Apple Watch');
        $response->assertSee('Pineapple Case');
        $response->assertDontSee('Galaxy Phone');
    }

    /* 検索状態がマイリストでも保持されている */
    public function test_search_keyword_keep_mylist()
    {
        $apple = Item::factory()->create(['name' => 'Apple Watch']);
        Item::factory()->create(['name' => 'Galaxy Phone']);
        Item::factory()->create(['name' => 'Pineapple Case']);

        $user = User::factory()->withProfile()->create();
        Like::factory()->create(['item_id' => $apple->id, 'user_id' => $user->id]);

        $this->actingAs($user);
        $response = $this->get('/?keyword=Apple');
        $response->assertSee('Apple Watch');
        $response->assertSee('Pineapple Case');
        $response->assertDontSee('Galaxy Phone');

        $response = $this->get('/?tab=mylist');
        $response->assertStatus(200);
        $response->assertSee('Apple Watch');
        $response->assertDontSee('Pineapple Case');
        $response->assertDontSee('Galaxy Phone');
    }
}

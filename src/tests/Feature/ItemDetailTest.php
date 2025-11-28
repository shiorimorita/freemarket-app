<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;
use App\Models\User;
use App\Models\Profile;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 必要な情報が表示される */
    public function test_item_detail()
    {
        $seller = User::factory()->withProfile()->create();

        /* 商品、カテゴリを作成 */
        $category = Category::factory()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);
        $item->categories()->attach($category->id);

        $user = User::factory()
            ->has(Profile::factory()->state([
                'image_path' => 'images/test.jpg',
            ]))
            ->create();

        /* いいね、コメントを設定 */
        Like::factory()->create(['item_id' => $item->id, 'user_id' => $user->id]);
        $comment = Comment::factory()->create(['item_id' => $item->id, 'user_id' => $user->id, 'content' => 'テストコメントです。']);

        $response = $this->get("item/{$item->id}")->assertStatus(200);

        $response->assertSee("storage/{$item->image_path}", false);
        $response->assertSee($item->name, false);
        $response->assertSee($item->brand, false);
        $response->assertSee(number_format($item->price), false);
        $response->assertSee($item->description, false);
        $response->assertSee($category->name, false);
        $response->assertSee($item->status, false);
        $response->assertSee($comment->user->name, false);
        $response->assertSee($comment->user->image_path, false);
        $response->assertSee($comment->content, false);
        $response->assertSee('<span class="comment__count">1</span>', false);
        $response->assertSee('<span class="like__count">1</span>', false);
        $response->assertSee('<span class="item-comment__count">(1)</span>', false);
    }

    /* 複数選択されたカテゴリが表示されているか */
    public function test_item_categories()
    {
        $item = Item::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories);

        $response = $this->get("item/{$item->id}")->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}

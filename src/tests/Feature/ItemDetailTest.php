<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Category;
use App\Models\User;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_item_detail()
    {
        $item = Item::factory()->create();
        $user = User::factory()->withProfile()->create();

        /* いいね */
        Like::factory()->create(['item_id' => $item->id]);

        /* コメント */
        $comment = Comment::factory()->create(['item_id' => $item->id, 'user_id' => $user->id]);

        /* カテゴリー */
        $category = Category::factory()->create();
        $item->categories()->attach($category->id);

        $response = $this->get("item/{$item->id}");

        $response->assertStatus(200);
        $response->assertSeeInOrder(
            [
                "storage/{$item->image_path}",
                $item->name,
                $item->brand,
                number_format($item->price),
                $item->description,
                $item->status,
            ]
        );
        $response->assertSee('<span class="comment__count">1</span>', false);
        $response->assertSee('<span class="like__count">1</span>', false);
        $response->assertSee($category->name);
        $response->assertSee($user->name);
        $response->assertSee($comment->content);
    }

    /* 複数選択されたカテゴリが表示されているか */
    public function test_item_categories()
    {
        $item = Item::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $item->categories()->attach($categories);

        $response = $this->get("item/{$item->id}");
        $response->assertStatus(200);

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }
}

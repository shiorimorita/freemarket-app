<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* ログイン済みのユーザーはコメントを送信できる */
    public function test_comment_create()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $response = $this->get("item/{$item->id}");
        $response->assertStatus(200);
        $response->assertSee('<span class="comment__count">0</span>', false);

        $this->post("item/{$item->id}/comment", ['content' => 'こちらお値引き可能でしょうか']);
        $this->assertDatabaseHas('comments', ['user_id' => $user->id, 'item_id' => $item->id, 'content' => 'こちらお値引き可能でしょうか']);

        $response = $this->get("item/{$item->id}");
        $response->assertSee('<span class="comment__count">1</span>', false);
    }

    /* ログイン前のユーザーはコメントを送信できない */
    public function test_comment_no_user()
    {
        $item = Item::factory()->create();
        $response = $this->post("item/{$item->id}/comment", ['content' => 'こちらお値引き可能でしょうか']);
        $response->assertRedirect('/login');

        $this->assertDatabaseCount('comments', 0);
    }

    /* コメントが入力されていない場合、バリデーションメッセージが表示される */
    public function test_comment_input_error()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $response = $this->post("item/{$item->id}/comment", ['content' => '']);
        $response->assertSessionHasErrors([
            'content' => 'コメントを入力してください'
        ]);
    }

    /* コメントが255字以上の場合、バリデーションメッセージが表示される */
    public function test_comment_max_error()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);
        $user = User::factory()->withProfile()->create();
        $this->actingAs($user);

        $longText = str_repeat('あ', 256);
        $response = $this->post("item/{$item->id}/comment", ['content' => $longText]);
        $response->assertSessionHasErrors(['content' => 'コメントを255文字以内で入力してください']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Sold;

class ProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧） */
    public function test_user_sell_buy_profile_confirm()
    {
        $profileOwner = User::factory()->create(['name' => 'テストユーザー']);
        Profile::factory()->create(['user_id' => $profileOwner->id, 'image_path' => 'test.jpg',]);
        /** @var \App\Models\User $profileOwner */
        $this->actingAs($profileOwner);

        /* 出品、購入ページにあらかじめ商品が存在しないことを確認 */
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertDontSee('BUY_ITEM');
        $response->assertDontSee('buy_image.png');

        $response = $this->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertDontSee('SELL_ITEM');
        $response->assertDontSee('sell_image.png');

        /* 出品した商品 */
        $sellItems = Item::factory()->count(3)->create(['user_id' => $profileOwner->id, 'name' => 'SELL_ITEM', 'image_path' => 'sell_image.png']);

        /* 購入した商品 */
        $seller = User::factory()->create();
        $buyItems = Item::factory()->count(3)->create(['user_id' => $seller->id]);

        foreach ($buyItems as $item) {
            Sold::factory()->create([
                'user_id' => $profileOwner->id,
                'item_id' => $item->id,
            ]);
        }

        /* 出品、購入商品を確認 */
        $response = $this->get('/mypage?page=buy');
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');

        foreach ($buyItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee($item->image_path);
        }

        $response = $this->get('/mypage?page=sell');
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');

        foreach ($sellItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee($item->image_path);
        }
    }

    /* /* 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所） */
    public function test_user_profile_confirm()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);

        Profile::factory()->create([
            'user_id' => $user->id,
            'image_path' => 'test.jpg',
            'post_code' => '111-1111',
            'address'   => '東京都港区1-1-1',
            'building'  => 'テストB202号室',
        ]);

        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $response = $this->get('/mypage/profile');
        $response->assertStatus(200);

        $response->assertSee('テストユーザー');
        $response->assertSee('test.jpg');
        $response->assertSee('111-1111');
        $response->assertSee('東京都港区1-1-1');
        $response->assertSee('テストB202号室');
    }
}

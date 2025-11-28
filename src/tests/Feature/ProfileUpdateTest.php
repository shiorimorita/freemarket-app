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
        $profileOwner = User::factory()->create();
        $profileImage = 'images/test.jpg';
        Profile::factory()->create(['user_id' => $profileOwner->id, 'image_path' => $profileImage]);

        /** @var \App\Models\User $profileOwner */
        $this->actingAs($profileOwner);

        /* 出品した商品 */
        $sellItems = Item::factory()->count(3)->create(['user_id' => $profileOwner->id]);

        /* 購入した商品 */
        $seller = User::factory()->create();
        $buyItems = Item::factory()->count(3)->create(['user_id' => $seller->id]);

        foreach ($buyItems as $item) {
            Sold::factory()->create([
                'user_id' => $profileOwner->id,
                'item_id' => $item->id,
            ]);
        }

        /* 購入した商品を確認 */
        $response = $this->get('/mypage?page=buy')->assertStatus(200);
        $response->assertSee($profileOwner->name);
        $response->assertSee($profileImage);

        foreach ($buyItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee($item->image_path);
            $response->assertSee('<span class="sold-badge sold-badge--mypage">Sold</span>', false);
        }

        /* 出品した商品を確認 */
        $response = $this->get('/mypage?page=sell')->assertStatus(200);
        $response->assertSee($profileOwner->name);
        $response->assertSee($profileImage);

        foreach ($sellItems as $item) {
            $response->assertSee($item->name);
            $response->assertSee($item->image_path);
        }
    }

    /* 変更項目が初期値として過去設定されていること（プロフィール画像、ユーザー名、郵便番号、住所） */
    public function test_user_profile_confirm()
    {
        $user = User::factory()->create();
        $profileImage = 'images/test.jpg';

        $profile = Profile::factory()->create(['user_id' => $user->id, 'image_path' => $profileImage,]);

        /** @var \App\Models\User $user */
        $this->actingAs($user);

        $response = $this->get('/mypage/profile')->assertStatus(200);

        $response->assertSee($user->name);
        $response->assertSee($profile->image_path);
        $response->assertSee($profile->post_code);
        $response->assertSee($profile->address);
    }
}

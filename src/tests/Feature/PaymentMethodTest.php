<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 小計画面で変更が反映される */
    public function test_method_view()
    {
        $seller = User::factory()->withProfile()->create();
        $item = Item::factory()->create(['user_id' => $seller->id]);

        $buyer = User::factory()->withProfile()->create();
        $this->actingAs($buyer);

        $response = $this->get("/purchase/{$item->id}");
        $response->assertDontSee('<dd class="checkout__detail-term" id="selected_payment">コンビニ払い</dd>', false);

        $response = $this->post("/purchase/method/{$item->id}", ['method' => 'コンビニ払い']);
        $response = $this->get("/purchase/{$item->id}");
        $response->assertSee('<dd class="checkout__detail-term" id="selected_payment">コンビニ払い</dd>', false);
    }
}

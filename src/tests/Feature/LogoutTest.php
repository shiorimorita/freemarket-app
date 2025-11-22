<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* ログアウトができる */
    public function test_logout_success()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test0000@example.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        /** @var \App\Models\User $user */
        $this->actingAs($user);
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}

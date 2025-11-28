<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\URL;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* 会員登録後、認証メールが送信される */
    public function test_register_user_receives_verification_email()
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'test@example.com')->first();

        /* 会員登録済み＆メール認証送信を確認 */
        $this->assertNotNull($user);
        Notification::assertSentTo($user, VerifyEmail::class);
    }

    /* メール認証誘導画面で「認証はこちらから」ボタンを押下するとメール認証サイトに遷移する */
    public function test_verify_email_page_contains_auth_link()
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create(['email_verified_at' => null]);
        $this->actingAs($user);

        $response = $this->get('/email/verify');
        $response->assertStatus(200);

        $response->assertSee('認証はこちらから');
        $response->assertSee('http://localhost:8025');
    }

    /* メール認証サイトのメール認証を完了すると、プロフィール設定画面に遷移する */
    public function test_verified_user_redirects_to_profile_setting_page()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        /** @var \App\Models\User $user */
        $response = $this->actingAs($user)->get($verificationUrl);
        $this->assertNotNull($user->fresh()->email_verified_at);
        $response->assertRedirect('/mypage/profile');
        $this->get('/mypage/profile')->assertStatus(200);
    }
}

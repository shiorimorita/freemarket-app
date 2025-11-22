<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /* メールアドレスが入力されていない場合、バリデーションメッセージが表示される */
    public function test_login_email_required()
    {
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /* パスワードが入力されていない場合、バリデーションメッセージが表示される */
    public function test_login_password_required()
    {
        $response = $this->post('/login', [
            'email' => 'test0000@example.com',
            'password' => '',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /* 入力情報が間違っている場合、バリデーションメッセージが表示される */
    public function test_login_user_failed()
    {
        $response = $this->post('/login', [
            'email' => 'notexist@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors(['email' => 'ログイン情報が登録されていません']);
    }

    /* 正しい情報が入力された場合、ログイン処理が実行される */
    public function test_login_user_success()
    {
        $user = User::factory()->create([
            'name' => 'テストユーザー',
            'email' => 'test0000@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test0000@example.com',
            'password' => 'password',
        ]);

        $user->markEmailAsVerified();
        $response->assertRedirect('/');
        $redirect = $this->get('/');
        $redirect->assertRedirect('/mypage/profile');
        $this->assertAuthenticatedAs($user);
    }
}

<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /* 名前が入力されていない場合、バリデーションメッセージが表示される */
    public function test_register_name_required()
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test0000@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['name' => 'お名前を入力してください']);
    }

    /* メールアドレスが入力されていない場合、バリデーションメッセージが表示される */
    public function test_register_email_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => '',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    /* パスワードが入力されていない場合、バリデーションメッセージが表示される */
    public function test_register_password_required()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test0000@example.com',
            'password' => '',
            'password_confirmation' => 'password'
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードを入力してください']);
    }

    /* パスワードは8文字以上で入力してください」というバリデーションメッセージが表示される */
    public function test_register_password_min()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test0000@example.com',
            'password' => 'passwor',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    /* パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される */
    public function test_register_password_confirmation_mismatch()
    {
        $response = $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test0000@example.com',
            'password' => 'password',
            'password_confirmation' => 'qazwsxwdc',
        ]);

        $response->assertSessionHasErrors(['password_confirmation' => 'パスワードと一致しません']);
    }

    /* 全ての項目が入力されている場合、会員情報が登録され、プロフィール設定画面に遷移される */
    public function test_register_success_register()
    {
        $this->post('/register', [
            'name' => 'テストユーザー',
            'email' => 'test0000@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test0000@example.com',
        ]);

        $user = User::where('email', 'test0000@example.com')->first();
        $user->markEmailAsVerified();
        $this->actingAs($user);
        $redirect = $this->get('/');
        $redirect->assertRedirect('/mypage/profile');
        $this->assertAuthenticatedAs($user);
    }
}

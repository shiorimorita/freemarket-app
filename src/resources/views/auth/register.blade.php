@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection
@section('content')
<main class="register">
    <form action="/register" method="post" class="register__form">
        @csrf
        <div class="register__inner">
            <h2 class="register__title common-sub-title">会員登録</h2>
            <label for="name" class="register__label">ユーザー名</label>
            <input type="text" name="name" id="name" class="register__input">
            <label for="email" class="register__label">メールアドレス</label>
            <input type="email" name="email" id="email" class="register__input">
            <label for="password" class="register__label">パスワード</label>
            <input type="password" name="password" id="password" class="register__input">
            <label for="password_confirmation" class="register__label">確認用パスワード</label>
            <input type="password" name="password_confirmation" class="register__input" id="password_confirmation">
        </div>
        <div class="register__buttons">
            <button type="submit" class="register__button common-btn">登録する</button>
            <a href="" class="register__link common-link">ログインはこちら</a>
        </div>
    </form>
</main>
@endsection
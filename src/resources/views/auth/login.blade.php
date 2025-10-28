@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection
@section('content')
<main class="login">
    <form action="/login" method="post" class="login__form">
        @csrf
        <div class="login__inner">
            <h2 class="login__title common-sub-title">ログイン</h2>
            <label for="email" class="login__label">メールアドレス</label>
            <input type="email" name="email" id="email" class="login__input">
            <label for="password" class="login__label">パスワード</label>
            <input type="password" name="password" id="password" class="login__input">
            <div class="login__buttons">
                <button type="submit" class="login__button common-btn">ログインする</button>
                <a href="" class="login__link common-link">会員登録はこちら</a>
            </div>
        </div>
    </form>
</main>
@endsection
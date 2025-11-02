@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/login.css')}}">
@endsection
@section('content')
<main class="login">
    <form action="/login" method="post" class="login__form">
        @csrf
        <h2 class="login__title common-sub-title">ログイン</h2>
        <div class="login__group">
            <label for="email" class="login__label">メールアドレス</label>
            <input type="mail" name="email" id="email" class="login__input" value="{{old('email')}}">
            <p class="login_error input_error">
                @error('email')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="login__group">
            <label for="password" class="login__label">パスワード</label>
            <input type="password" name="password" id="password" class="login__input">
            <p class="login_error input_error">
                @error('password')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="login__buttons">
            <button type="submit" class="login__button common-btn">ログインする</button>
            <a href="/register" class="login__link common-link">会員登録はこちら</a>
        </div>
    </form>
</main>
@endsection
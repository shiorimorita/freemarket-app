@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
@endsection

@section('content')
<main class="register">
    <form action="/register" method="post" class="register__form">
        @csrf
        <h2 class="register__title common-sub-title">会員登録</h2>
        <div class="register__group">
            <label for="name" class="register__label">ユーザー名</label>
            <input type="text" name="name" id="name" class="register__input" value="{{old('name')}}">
            <p class="input-error">
                @error('name')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="register__group">
            <label for="email" class="register__label">メールアドレス</label>
            <input type="mail" name="email" id="email" class="register__input" value="{{old('email')}}">
            <p class="input-error">
                @error('email')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="register__group">
            <label for="password" class="register__label">パスワード</label>
            <input type="password" name="password" id="password" class="register__input">
            <p class="input-error">
                @error('password')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="register__group">
            <label for="password_confirmation" class="register__label">確認用パスワード</label>
            <input type="password" name="password_confirmation" class="register__input" id="password_confirmation">
            <p class="input-error">
                @error('password_confirmation')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="register__buttons">
            <button type="submit" class="register__button common-btn">登録する</button>
            <a href="/login" class="register__link common-link">ログインはこちら</a>
        </div>
    </form>
</main>
@endsection
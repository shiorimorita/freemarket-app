@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/profile.css')}}">
<script src="{{asset('js/common.js')}}" defer></script>
@endsection
@section('content')
<main class="profile">
    <form action="/mypage" method="post" class="profile__form" enctype="multipart/form-data">
        @csrf
        <h2 class="profile__title common-sub-title">プロフィール設定</h2>
        <div class="profile__img">
            <img src="" alt="" class="profile__img-image">
            <input type="file" name="image_path" class="profile__img-input">
            <button class="profile__img-button" type="button">画像を選択する</button>
            <p class="profile_error input_error">
                @error('image_path')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile__group">
            <label for="name" class="profile__label">ユーザー名</label>
            <input type="text" name="" id="name" class="profile__input" value="{{old('name')}}">
            <p class="profile_error input_error">
                @error('name')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile__group">
            <label for="post_code" class="profile__label">郵便番号</label>
            <input type="text" name="post_code" id="post_code" class="profile__input" value="{{old('post_code')}}">
            <p class="profile_error input_error">
                @error('post_code')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile__group">
            <label for="address" class="profile__label">住所</label>
            <input type="text" name="address" id="address" class="profile__input" value="{{old('address')}}">
            <p class="profile_error input_error">
                @error('address')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="profile__group">
            <label for="building" class="profile__label">建物</label>
            <input type="text" name="building" id="building" class="profile__input" value="{{old('building')}}">
        </div>
        <button type="submit" class="profile__button common-btn">更新する</button>
    </form>
</main>
@endsection
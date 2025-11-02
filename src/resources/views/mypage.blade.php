@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/mypage.css')}}">
@endsection
@section('content')
<div class="mypage">
    <div class="mypage__profile-info">
        <img src="" alt="" class="mypage__profile-img">
        <p class="mypage__profile-user">ユーザー名</p>
        <a href="" class="mypage__profile-edit common__img-button">プロフィール編集</a>
    </div>
    <nav class="mypage__profile-links">
        <ul class="mypage__profile-list">
            <li class="mypage__profile-item">
                <a href="" class="mypage__profile-link mypage__profile-link--active">出品した商品</a>
            </li>
            <li class="mypage__profile-item">
                <a href="" class="mypage__profile-link mypage__profile-link--active">購入した商品</a>
            </li>
        </ul>
    </nav>
    <div class="mypage__items">
        <div class="mypage__item">
            <img src="" alt="" class="mypage__item-img">
            <p class="mypage__item-name">商品名</p>
        </div>
        <div class="mypage__item">
            <img src="" alt="" class="mypage__item-img">
            <p class="mypage__item-name">商品名</p>
        </div>
        <div class="mypage__item">
            <img src="" alt="" class="mypage__item-img">
            <p class="mypage__item-name">商品名</p>
        </div>
        <div class="mypage__item">
            <img src="" alt="" class="mypage__item-img">
            <p class="mypage__item-name">商品名</p>
        </div>
        <div class="mypage__item">
            <img src="" alt="" class="mypage__item-img">
            <p class="mypage__item-name">商品名</p>
        </div>
    </div>
</div>
@endsection
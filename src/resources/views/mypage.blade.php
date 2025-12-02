@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')
<div class="mypage">
    <div class="mypage__profile-info">
        @if($user->profile->image_path)
        <img src="{{ asset('storage/' . $user->profile->image_path)}}" alt="{{$user->name}} さんのプロフィール画像" class="mypage__profile-img">
        @else
        <img src="" alt="" class="mypage__profile-no-img">
        @endif
        <p class="mypage__profile-user">{{ $user->name }}</p>
        <a href="/mypage/profile" class="mypage__profile-edit common-img-button">プロフィール編集</a>
    </div>
    <nav class="mypage__profile-links">
        <ul class="mypage__profile-list">
            <li class="mypage__profile-item">
                <a href="/mypage?page=sell" class="mypage__profile-link {{ $page == 'sell' ? 'mypage__profile-link--active' : '' }}">出品した商品</a>
            </li>
            <li class="mypage__profile-item">
                <a href="/mypage?page=buy" class="mypage__profile-link {{ $page == 'buy' ? 'mypage__profile-link--active' : '' }}">購入した商品</a>
            </li>
        </ul>
    </nav>
    <div class="mypage__items">
        @foreach ($items as $item)
        <div class="mypage__item">
            <div class="mypage__item-image-wrapper">
                <a href="/item/{{ $item->id }}" class="mypage__item-link">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="mypage__item-img">
                    @if ($item->is_sold)
                    <span class="sold-badge sold-badge--mypage">Sold</span>
                    @endif
                </a>
            </div>
            <p class="mypage__item-name">{{ $item->name }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
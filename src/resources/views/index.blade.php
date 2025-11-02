@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/index.css')}}">
@endsection
@section('content')
<div class="products">
    <ul class="product-tabs__list">
        <li class="product-tabs__item">
            <a href="" class="product-tabs__link">おすすめ</a>
        </li>
        <li class="product-tabs__item">
            <a href="" class="product-tabs__link">マイリスト</a>
        </li>
    </ul>
    <div class="product-list">
        <div class="product-card">
            <img src="" alt="" class="product-card__img">
            <p class="product-card__name">商品名</p>
        </div>
    </div>
</div>
@endsection
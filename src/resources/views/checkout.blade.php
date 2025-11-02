@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/checkout.css')}}">
@endsection
@section('content')
<main class="checkout">
    <form action="" method="post" class="checkout__form">
        @csrf
        <div class="checkout__left">
            <div class="checkout__item">
                <div class="checkout__item-img">
                    <img src="" alt="" class="checkout__item-image">
                </div>
                <div class="checkout__item-info">
                    <h2 class="checkout__item-name">商品名</h2>
                    <p class="checkout__item-price">¥&nbsp;47,000</p>
                </div>
            </div>
            <div class="checkout__method">
                <label class="checkout__method-label" for="payment_method">支払い方法</label>
                <select name="" class="checkout__method-select" id="payment_method">
                    <option disabled selected hidden>選択してください</option>
                    <option>コンビニ払い</option>
                    <option>カード払い</option>
                </select>
            </div>
            <div class="checkout__delivery">
                <div class="checkout__delivery-info">
                    <p class="checkout__delivery-label">配送先</p>
                    <a href="" class="checkout__delivery-link common-link">変更する</a>
                </div>
                <div class="checkout__delivery-address">
                    <p class="checkout__delivery-post-code">〒 XXX-YYYY</p>
                    <p class="checkout__delivery-text">ここには住所と建物が入ります</p>
                </div>
            </div>
        </div>
        <div class="checkout__right">
            <dl class="checkout__detail">
                <dt class="checkout__detail-title">商品代金</dt>
                <dd class="checkout__detail-term">¥ 47,000</dd>
                <dt class="checkout__detail-title">支払い方法</dt>
                <dd class="checkout__detail-term">コンビニ払い</dd>
            </dl>
            <button type="submit" class="checkout__button common-btn">購入する</button>
        </div>
    </form>
</main>
@endsection
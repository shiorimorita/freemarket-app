@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/checkout.css') }}">
@endsection

@section('content')
<main class="checkout">
    <form action="/purchase/{{ $item->id }}" method="post" class="checkout__form" id="pay-form">
        @csrf
        <div class="checkout__left">
            <div class="checkout__item">
                <div class="checkout__item-img">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->name }}" class="checkout__item-image">
                </div>
                <div class="checkout__item-info">
                    <h2 class="checkout__item-name">{{ $item->name }}</h2>
                    <p class="checkout__item-price">
                        <span class="checkout__item-symbol">¥</span>
                        <span class="checkout__item-value">{{ number_format($item->price) }}</span>
                    </p>
                </div>
            </div>
            <div class="checkout__method">
                <label class="checkout__method-label">支払い方法</label>
                <select name="method" class="checkout__method-select" id="payment_method">
                    <option disabled selected hidden>選択してください</option>
                    <option value="コンビニ払い" {{ session("method_{$item->id}") === 'コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="カード払い" {{ session("method_{$item->id}") === 'カード払い' ? 'selected' : '' }}>カード払い</option>
                </select>
                <p class="input-error">
                    @error('method')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="checkout__delivery">
                <div class="checkout__delivery-info">
                    <p class="checkout__delivery-label">配送先</p>
                    <a href="/purchase/address/{{ $item->id }}" class="common-link">変更する</a>
                </div>
                <div class="checkout__delivery-address">
                    <div class="checkout__post-code-wrapper">
                        <span class="checkout__post-code-prefix">〒</span>
                        <p class="checkout__delivery-post-code">{{ $delivery['post_code'] }}</p>
                    </div>
                    <p class="checkout__delivery-text">{{ $delivery['address'] }}</p>
                    <p class="checkout__delivery-text">{{ $delivery['building'] }}</p>
                </div>
                <p class="input-error">
                    @error('delivery')
                    {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
        <div class="checkout__right">
            <dl class="checkout__detail">
                <dt class="checkout__detail-title">商品代金</dt>
                <dd class="checkout__detail-term">¥ {{ number_format($item->price) }}</dd>
                <dt class="checkout__detail-title">支払い方法</dt>
                <dd class="checkout__detail-term" id="selected_payment">{{ $method }}</dd>
            </dl>
            <button type="submit" class="checkout__button common-btn" id="purchase-button">購入する</button>
        </div>
    </form>
</main>
<script>
    const form = document.getElementById('pay-form');
    const method = document.getElementById('payment_method');
    const itemId = "{{ $item->id }}";
    const selectedPayment = document.getElementById('selected_payment');

    // 支払方法選択後にpost
    method.addEventListener('change', function () {

        const data = new FormData();
        data.append('method', this.value);

        fetch(`/purchase/method/{{ $item->id }}`, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: data
        });

        selectedPayment.textContent = this.value;
    });

    // 購入確定ボタンの連打を制御
    document.addEventListener('DOMContentLoaded', () => {
        const button = document.querySelector('#purchase-button');

        button.addEventListener('click', function () {
            this.disabled = true;
            this.form.submit();
        });
    })
</script>
@endsection
@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/checkout.css')}}">
@endsection
@section('content')
<main class="checkout">
    <form action="/purchase/{{$item->id}}" method="post" class="checkout__form" id="pay-form">
        @csrf
        <div class="checkout__left">
            <div class="checkout__item">
                <div class="checkout__item-img">
                    <img src="{{asset('storage/'. $item->image_path)}}" alt="" class="checkout__item-image">
                </div>
                <div class="checkout__item-info">
                    <h2 class="checkout__item-name">{{$item->name}}</h2>
                    <p class="checkout__item-price">¥&nbsp;{{$item->price}}</p>
                </div>
            </div>
            <div class="checkout__method">
                <label class="checkout__method-label">支払い方法</label>
                <select name="method" class="checkout__method-select" id="payment_method">
                    <option disabled selected hidden>選択してください</option>
                    <option value="コンビニ払い" {{old('method')==='コンビニ払い' ? 'selected' : '' }}>コンビニ払い</option>
                    <option value="カード払い" {{old('method')==='カード払い' ? 'selected' : '' }}>カード払い</option>
                </select>
                <p class="checkout_error input-error">
                    @error('method')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="checkout__delivery">
                <div class="checkout__delivery-info">
                    <p class="checkout__delivery-label">配送先</p>
                    <a href="/purchase/address/{{$item->id}}" class="checkout__delivery-link common-link">変更する</a>
                </div>
                <div class="checkout__delivery-address">
                    <p class="checkout__delivery-post-code">{{$post_code}}</p>
                    <input type="hidden" name="post_code" value="{{$post_code}}">
                    <p class="checkout__delivery-text">{{$address}}</p>
                    <input type="hidden" name="address" value="{{$address}}">
                    <p class="checkout__delivery-text">{{$building}}</p>
                    <input type="hidden" name="building" value="{{$building}}">
                </div>
                <p class="checkout_error input-error">
                    @error('delivery')
                    {{ $message }}
                    @enderror
                </p>
            </div>
        </div>
        <div class="checkout__right">
            <dl class="checkout__detail">
                <dt class="checkout__detail-title">商品代金</dt>
                <dd class="checkout__detail-term">¥ {{ number_format($item->price)}}</dd>
                <dt class="checkout__detail-title">支払い方法</dt>
                <dd class="checkout__detail-term" id="selected_payment"></dd>
            </dl>
            @if($item->is_sold)
            <button type="submit" class="checkout__button common-btn" disabled style="background: #ccc; cursor: not-allowed;">購入できません</button>
            @else
            <button type="submit" class="checkout__button common-btn">購入する</button>
            @endif
        </div>
    </form>
</main>
<script>
    const form = document.getElementById('pay-form');
    const method = document.getElementById('payment_method');
    const itemId = "{{ $item->id }}";
    const selectedPayment = document.getElementById('selected_payment');

    // 支払い方法選択時に表示更新
    method.addEventListener('change', function () {
        selectedPayment.textContent = this.value;
    });

    form.addEventListener('submit', function (e) {
        if (method.value === 'コンビニ払い') {
            // Edge でも確実に開く
            const konbiniUrl = `/pay/konbini/${itemId}`;
            window.open(konbiniUrl, '_blank');

            // 元のタブは / に戻す
            setTimeout(() => {
                window.location.href = "/";
            }, 200);
        }
    });
</script>
@endsection
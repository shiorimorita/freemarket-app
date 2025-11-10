@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/checkout.css')}}">
@endsection
@section('content')
<main class="checkout">
    <form action="/purchase/{{$item->id}}" method="post" class="checkout__form">
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
                <p class="checkout_error input_error">
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
                    <p class="checkout__delivery-post-code">{{ optional($delivery)->post_code}}</p>
                    <p class="checkout__delivery-text">{{optional($delivery)->address}}</p>
                    <p class="checkout__delivery-text">{{optional($delivery)->building}}</p>
                </div>
                <p class="checkout_error input_error">
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
            <button type="submit" class="checkout__button common-btn">購入する</button>
        </div>
    </form>
</main>
<script>
    document.querySelector('#payment_method').addEventListener('change', (e) => {
        const selected = e.target.value;
        document.querySelector('#selected_payment').textContent = selected;
    });
</script>
@endsection
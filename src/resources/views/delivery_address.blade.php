@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/delivery_address.css') }}">
@endsection

@section('content')
<main class="delivery">
    <h2 class="delivery__title common-sub-title">住所の変更</h2>
    <form action="/purchase/address/{{ $item->id }}" method="post" class="delivery__form">
        @csrf
        <div class="delivery__group">
            <label for="post_code" class="delivery__label">郵便番号</label>
            <input type="text" name="post_code" id="post_code" class="delivery__input" value="{{ old('post_code', $delivery['post_code']) }}">
            <p class="input-error">
                @error('post_code')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="delivery__group">
            <label for="address" class="delivery__label">住所</label>
            <input type="text" name="address" id="address" class="delivery__input" value="{{ old('address', $delivery['address']) }}">
            <p class="input-error">
                @error('address')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="delivery__group">
            <label for="building" class="delivery__label">建物名</label>
            <input type="text" name="building" id="building" class="delivery__input" value="{{ old('building', $delivery['building']) }}">
        </div>
        <button type="submit" class="delivery__button common-btn">更新する</button>
    </form>
</main>
@endsection
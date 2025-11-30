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
            <input type="text" name="post_code" id="post_code" class="delivery__input" value="{{ old('post_code', optional($delivery)->post_code) }}">
            <p class="input-error">
                @error('post_code')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="delivery__group">
            <label for="address" class="delivery__label">住所</label>
            <input type="text" name="address" id="address" class="delivery__input" value="{{ old('address', optional($delivery)->address) }}">
            <p class="input-error">
                @error('address')
                {{ $message }}
                @enderror
            </p>
        </div>
        <div class="delivery__group">
            <label for="building" class="delivery__label">建物名</label>
            <input type="text" name="building" id="building" class="delivery__input" value="{{ old('building', optional($delivery)->building) }}">
        </div>
        @if ($item->user_id === Auth::id())
        <button type="button" class="delivery__button btn--disabled">自分の商品は配送先の設定ができません</button>
        @elseif ($item->is_sold)
        <button type="button" class="delivery__button btn--disabled">配送先を変更できません</button>
        @else
        <button type="submit" class="delivery__button common-btn">更新する</button>
        @endif
    </form>
</main>
@endsection
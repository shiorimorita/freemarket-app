@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/delivery_address.css')}}">
@endsection
@section('content')
<main class="delivery">
    <form action="" method="post" class="delivery__form">
        @csrf
        <h2 class="delivery__title common-sub-title">住所の変更</h2>
        <div class="delivery__group">
            <label for="" class="delivery__label">郵便番号</label>
            <input type="text" name="" id="" class="delivery__input" value="">
            <p class="delivery__error input_error">
                @error('')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="delivery__group">
            <label for="" class="delivery__label">住所</label>
            <input type="text" name="" id="" class="delivery__input" value="">
            <p class="delivery__error input_error">
                @error('')
                {{$message}}
                @enderror
            </p>
        </div>
        <div class="delivery__group">
            <label for="" class="delivery__label">建物名</label>
            <input type="text" name="" id="" class="delivery__input" value="">
            <p class="delivery__error input_error">
                @error('')
                {{$message}}
                @enderror
            </p>
        </div>
        <button type="submit" class="delivery__button common-btn">更新する</button>
    </form>
</main>
@endsection
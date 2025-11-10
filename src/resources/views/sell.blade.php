@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/sell.css')}}">
<script src="{{asset('js/common.js')}}" defer></script>
@endsection
@section('content')
<main class="sell">
    <form action="/sell" method="post" class="sell__form" enctype="multipart/form-data">
        @csrf
        <h2 class="sell__title common-sub-title">商品の出品</h2>
        <fieldset class="sell-images">
            <legend class="sell-images__legend">商品画像</legend>
            <div class="sell__img-group">
                <img src="" alt="" class="sell__img-image common__img-image">
                <input type="file" name="image_path" class="sell__img-input common__img-input">
                <label for="image" class="sell__img-button common__img-button">画像を選択する</label>
            </div>
            <p class="sell_error input_error">
                @error('image_path')
                {{ $message }}
                @enderror
            </p>
        </fieldset>
        <div class="sell__detail">
            <fieldset class="sell__sub-title-inner">
                <legend class="sell__sub-title">商品の詳細</legend>
                <div class="sell__group-category">
                    <span class="sell__label sell__label--category">カテゴリー</span>
                    <p class="sell_error input_error">
                        @error('category_ids')
                        {{ $message }}
                        @enderror
                    </p>
                    <div class="chips">
                        @foreach($categories as $category)
                        <input type="checkbox" name="category_ids[]" class="chip-check" id="{{$category->id}}" value="{{$category->id}}">
                        <label for="{{$category->id}}" class="chip">{{$category->name}}</label>
                        @endforeach
                    </div>
                </div>
                <div class="sell__group">
                    <label for="condition" class="sell__label">商品の状態</label>
                    <div class="select__inner">
                        <select name="condition" id="condition" class="sell__select">
                            <option value="" disabled selected hidden>選択してください</option>
                            <option value="良好" {{old('condition')=='良好' ? 'selected' : '' }}>良好</option>
                            <option value="目立った傷や汚れなし" {{old('condition')=='目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり" {{old('condition')=='やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                            <option value="状態が悪い" {{old('condition')=='状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                        </select>
                    </div>
                    <p class="sell_error input_error">
                        @error('condition')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
            </fieldset>
        </div>
        <fieldset class="sell__sub-title-inner">
            <legend class="sell__sub-title">商品名と説明</legend>
            <div class="sell__group-input">
                <label for="name" class="sell__label">商品名</label>
                <input type="text" name="name" class="sell__input" id="name" value="{{old('name')}}">
                <p class="sell_error input_error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell__group-input">
                <label for="brand" class="sell__label">ブランド名</label>
                <input type="text" name="brand" class="sell__input" id="brand" value="{{old('brand')}}">
            </div>
            <div class="sell__group-input">
                <label for="description" class="sell__label">商品の説明</label>
                <textarea name="description" id="description" class="sell__textarea" cols="30" rows="5">{{old('description')}}</textarea>
                <p class="sell_error input_error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell__group-input">
                <label for="price" class="sell__label">販売価格</label>
                <div class="price-wrapper">
                    <input type="text" name="price" class="sell__input sell__input--price" value="{{old('price')}}" id="price">
                </div>
                <p class="sell_error input_error">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button type="submit" class="sell__button common-btn">出品する</button>
        </fieldset>
    </form>
</main>
@endsection
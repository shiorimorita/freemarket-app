@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection
@section('js')
<script src="{{ asset('js/common.js') }}" defer></script>
@endsection

@section('content')
<main class="sell">
    <h2 class="sell__title common-sub-title">商品の出品</h2>
    <form action="/sell" method="post" class="sell__form" enctype="multipart/form-data">
        @csrf
        <fieldset class="sell__images">
            <legend class="sell__images-legend">商品画像</legend>
            <div class="sell__image-group">
                <img src="" class="sell__image js-img-preview">
                <input type="file" name="image_path" class="common-img-input">
                <button class="sell__img-button common-img-button" type="button">画像を選択する</button>
            </div>
            <p class="input-error">
                @error('image_path')
                {{ $message }}
                @enderror
            </p>
        </fieldset>
        <div class="sell__detail">
            <fieldset class="sell__section">
                <legend class="sell__sub-title">商品の詳細</legend>
                <div class="sell__group-category">
                    <span class="sell__label--category sell__label">カテゴリー</span>
                    <div class="sell__chips">
                        @foreach ($categories as $category)
                        <label for="{{ $category->id }}" class="sell__chip">
                            <input type="checkbox" name="category_ids[]" class="sell__chip-input" id="{{ $category->id }}" value="{{ $category->id }}" {{ is_array(old('category_ids')) && in_array($category->id, old('category_ids')) ? 'checked' : '' }}>
                            <span class="sell__chip-label">{{ $category->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    <p class="input-error">
                        @error('category_ids')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
                <div class="sell__group">
                    <label for="condition" class="sell__label">商品の状態</label>
                    <div class="select__inner">
                        <select name="condition" id="condition" class="sell__select">
                            <option value="" disabled hidden selected>選択してください</option>
                            <option value="良好" {{ old('condition')==='良好' ? 'selected' : '' }}>良好</option>
                            <option value="目立った傷や汚れなし" {{ old('condition')==='目立った傷や汚れなし' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                            <option value="やや傷や汚れあり" {{ old('condition')==='やや傷や汚れあり' ? 'selected' : '' }}>やや傷や汚れあり</option>
                            <option value="状態が悪い" {{ old('condition')==='状態が悪い' ? 'selected' : '' }}>状態が悪い</option>
                        </select>
                    </div>
                    <p class="input-error">
                        @error('condition')
                        {{ $message }}
                        @enderror
                    </p>
                </div>
            </fieldset>
        </div>
        <fieldset class="sell__section">
            <legend class="sell__sub-title">商品名と説明</legend>
            <div class="sell__group-input">
                <label for="name" class="sell__label">商品名</label>
                <input type="text" name="name" class="sell__input" id="name" value="{{ old('name') }}">
                <p class="input-error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell__group-input">
                <label for="brand" class="sell__label">ブランド名</label>
                <input type="text" name="brand" class="sell__input" id="brand" value="{{ old('brand') }}">
            </div>
            <div class="sell__group-input">
                <label for="description" class="sell__label">商品の説明</label>
                <textarea name="description" id="description" class="sell__textarea" cols="27">{{ old('description') }}</textarea>
                <p class="input-error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell__group-input">
                <label for="price" class="sell__label">販売価格</label>
                <div class="price-wrapper">
                    <input type="text" name="price" class="sell__input--price sell__input" value="{{ old('price') }}" id="price">
                </div>
                <p class="input-error">
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
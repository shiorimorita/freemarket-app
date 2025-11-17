@extends('layouts.common') @section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection @section('content')
<div class="products">
    <ul class="product-tabs__list">
        <li class="product-tabs__item">
            <a href="/" class="product-tabs__link  {{request('tab', 'recommend') === 'recommend'? 'product-tabs__link--active': ''}}">おすすめ</a>
        </li>
        <li class="product-tabs__item">
            <a href="/?tab=mylist" class="product-tabs__link {{request('tab', 'recommend') === 'mylist' ? 'product-tabs__link--active': ''}}">マイリスト</a>
        </li>
    </ul>
    <div class="product-list">
        @foreach ($items as $item)
        <div class="product-card">
            <a href="/item/{{ $item->id }}" class="product-card__link">
                <div class="product-card__image">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="" class="product-card__img" />
                    @if ($item->isSold)
                    <span class="sold-badge--list sold-badge">Sold</span>
                    @endif
                </div>
                <p class="product-card__name">{{ $item->name }}</p>
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection
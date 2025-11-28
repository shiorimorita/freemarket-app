@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}" />
@endsection

@section('content')
<!-- フラッシュメッセージ -->
@if (session('success'))
<div class="flash flash--success">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="flash flash--error">
    {{ session('error') }}
</div>
@endif

<main class="products">
    <ul class="product-tabs__list">
        <li class="product-tabs__item">
            <a href="/" class="product-tabs__link  {{$tab === 'recommend' ? 'product-tabs__link--active': ''}}">おすすめ</a>
        </li>
        <li class="product-tabs__item">
            <a href="/?tab=mylist" class="product-tabs__link {{$tab === 'mylist' ? 'product-tabs__link--active': ''}}">マイリスト</a>
        </li>
    </ul>
    <div class="product-list">
        @foreach($items as $item)
        <div class="product-card">
            <div class="product-card__image">
                <a href="/item/{{ $item->id }}" class="product-card__link">
                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品の画像" class="product-card__img" />
                    @if($item->is_sold)
                    <span class="sold-badge--list sold-badge">Sold</span>
                    @endif
                </a>
            </div>
            <p class="product-card__name">{{$item->name}}</p>
        </div>
        @endforeach
    </div>
</main>
@endsection
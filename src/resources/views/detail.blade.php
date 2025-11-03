@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection
@section('content')
<main class="item-detail">
    <div class="item-detail__left">
        <img src="{{asset('storage/' . $item->image_path)}}" alt="" class="item-detail__img">
    </div>
    <div class="item-detail__right">
        <h2 class="item__title">{{$item->name}}</h2>
        <span class="item__brand">{{$item->brand}}</span>
        <p class="item__price">
            <span class="item__price-symbol">¥</span>
            <span class="item__price-value">{{number_format($item->price)}}</span>
            <span class="item__price-tax">(税込)</span>
        </p>
        <div class="item__function">
            <div class="like">
                <form action="/item/{{$item->id}}/like" method="post" class="like__form">
                    @csrf
                    <button type="submit" class="like__button">
                        <img src="{{asset('storage/images/like.png')}}" alt="" class="like__button-img">
                    </button>
                    <span class="like__count">{{$item->likes_count}}</span>
                </form>
            </div>
            <div class="comment">
                <img src="{{asset('storage/images/comment.png')}}" alt="" class="comment__icon-img">
                <span class="comment__count">{{$item->comments->count()}}</span>
            </div>
        </div>
        <div class="purchase">
            <a href="" class="purchase__button common-btn">購入手続きへ</a>
        </div>
        <div class="item__description">
            <h2 class="item-sub__title sub-title">商品説明</h2>
            <p class="item__description-text">{{$item->description}}</p>
        </div>
        <div class="item__info">
            <h2 class="item-info__title sub-title">商品の情報</h2>
            <dl class="product-info__list">
                <dt class="product-info__term">カテゴリー</dt>
                @foreach($item->categories as $category)
                <dd class="product-info__desc">{{$category->name}}</dd>
                @endforeach
            </dl>
            <dl class="product-info__list">
                <dt class="product-info__term">商品の状態</dt>
                <dd class="product-info__desc">{{$item->condition}}</dd>
            </dl>
        </div>
        <div class="item__comment">
            <h2 class="item-comment__title">コメント
                <span class="item__comment-count">{{$item->comments->count()}}</span>
            </h2>
            @foreach($item->comments as $comment)
            <div class="item-comment__inner">
                <div class="item-comment__user">
                    <img src="{{asset('storage/' . $comment->user->profile->image_path)}}" alt="" class="comment-user__image">
                    <p class="comment-user__name">{{$comment->user->name}}</p>
                </div>
                <p class="comment__content">{{$comment->content}}</p>
            </div>
            @endforeach
            <h2 class="item-comment__sub-title">商品へのコメント</h2>
            <form action="/item/{{$item->id}}/comment" method="post" class="comment-form">
                @csrf
                <textarea name="content" cols="30" rows="5" class="comment__description"></textarea>
                <p class="comment_error input_error">
                    @error('content')
                    {{ $message }}
                    @enderror
                </p>
                <div class="comment__button">
                    <button class="comment-button__submit common-btn">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
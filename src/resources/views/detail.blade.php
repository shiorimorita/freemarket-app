@extends('layouts.common') @section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
@endsection @section('content')
<main class="item-detail">
    <div class="item-detail__left">
        <div class="item-detail__image">
            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像" class="item-detail__img" />
            @if ($isSold)
            <span class="sold-badge--detail sold-badge">Sold</span>
            @endif
        </div>
    </div>
    <div class="item-detail__right">
        <h2 class="item-detail__title">{{ $item->name }}</h2>
        <span class="item-detail__brand">{{ $item->brand }}</span>
        <p class="item-detail__price">
            <span class="item-detail__symbol">¥</span>
            <span class="item-detail__value">{{ number_format($item->price) }}</span>
            <span class="item-detail__tax">(税込)</span>
        </p>
        <div class="item-detail__function">
            <div class="like">
                <form action="/item/{{ $item->id }}/like" method="post" class="like__form">
                    @csrf
                    <button type="submit" class="like__button" aria-label="いいね">
                        <svg class="like__icon" viewBox="0 0 24 24" width="32" height="32" aria-hidden="true">
                            <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z" fill="{{ $liked ? 'red' : 'none' }}" stroke="#555" stroke-width="{{ $liked ? '0' : '1.5' }}" class="like__icon-path" />
                        </svg>
                    </button>
                    <span class="like__count">{{ $item->likes_count }}</span>
                </form>
            </div>
            <div class="comment">
                <img src="{{ asset('storage/images/comment.png') }}" alt="コメント数のアイコン" class="comment__icon-img" />
                <span class="comment__count">{{ $item->comments->count() }}</span>
            </div>
        </div>
        @if (Auth::id() !== $item->user_id && !$isSold)
        <div class="purchase">
            <a href="/purchase/{{ $item->id }}" class="purchase__button common-btn">購入手続きへ</a>
        </div>
        @elseif(Auth::id() === $item->user_id)
        <div class="purchase">
            <a href="#" class="purchase__button btn--disabled">自分の商品は購入できません</a>
        </div>
        @else
        <div class="purchase">
            <a href="#" class="purchase__button btn--disabled">売り切れのため購入できません</a>
        </div>
        @endif
        <div class="item-detail__description">
            <h2 class="item-detail__section-title">商品説明</h2>
            <p class="item-detail__description-text">{{ $item->description }}</p>
        </div>
        <div class="item-detail__info">
            <h2 class="item-detail__section-title">商品の情報</h2>
            <dl class="product-info__list">
                <dt class="product-info__term">カテゴリー</dt>
                @foreach ($item->categories as $category)
                <dd class="product-info__desc">{{ $category->name }}</dd>
                @endforeach
            </dl>
            <dl class="product-info__list">
                <dt class="product-info__term">商品の状態</dt>
                <dd class="product-info__desc">{{ $item->condition }}</dd>
            </dl>
        </div>
        <div class="item-comment">
            <h2 class="item-detail__section-title item-comment__title">コメント
                <span class="item-comment__count">({{ $item->comments->count() }})</span>
            </h2>
            @foreach ( $item->comments as $comment )
            <div class="item-comment__inner">
                <div class="item-comment__user">
                    @if (optional($comment->user->profile)->image_path)
                    <img src="{{ asset('storage/' . $comment->user->profile->image_path) }}" alt="コメント投稿者の画像" class="comment-user__image" />
                    @else
                    <div class="comment-user__image comment__no-img-bg"></div>
                    @endif
                    <p class="comment-user__name">{{ $comment->user->name }}</p>
                </div>
                <p class="comment__content">{{ $comment->content }}</p>
            </div>
            @endforeach
            <h2 class="item-comment__sub-title">商品へのコメント</h2>
            <form action="/item/{{ $item->id }}/comment" method="post" class="comment-form">
                @csrf
                <textarea name="content" cols="22" class="comment__description">{{ old("content") }}</textarea>
                <p class="input-error comment__error">
                    @error('content')
                    {{ $message }}
                    @enderror
                </p>
                <div class="comment__button">
                    @if(Auth::check() && Auth::user()->profile)
                    <button class="comment-button__submit common-btn">コメントを送信する</button>
                    @else
                    <button class="comment-button__submit common-btn" type="button">コメントを送信する</button>
                    @endif
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
@extends('layouts.common')
@section('css')
<link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection
@section('content')
<main class="item-detail">
    <div class="item-detail__left">
        <img src="{{asset('storage/images/Armani+Mens+Clock.jpg')}}" alt="" class="item-detail__img">
    </div>
    <div class="item-detail__right">
        <h2 class="item__title">※商品名</h2>
        <span class="item__brand">※ブランド名</span>
        <p class="item__price">
            <span class="item__price-symbol">¥</span>
            <span class="item__price-value">47,000</span>
            <span class="item__price-tax">(税込)</span>
        </p>
        <div class="item__function">
            <div class="like">
                <form action="" method="post" class="like__form">
                    @csrf
                    <button type="submit" class="like__button">
                        <img src="{{asset('storage/images/like.png')}}" alt="" class="like__button-img">
                    </button>
                    <span class="like__count">1</span>
                </form>
            </div>
            <div class="comment">
                <img src="{{asset('storage/images/comment.png')}}" alt="" class="comment__icon-img">
                <span class="comment__count">1</span>
            </div>
        </div>
        <div class="purchase">
            <a href="" class="purchase__button common-btn">購入手続きへ</a>
        </div>
        <div class="item__description">
            <h2 class="item-sub__title sub-title">商品説明</h2>
            <p class="item__description-text">
                ※カラー：グレー
                新品
                商品の状態は良好です。傷もありません。

                購入後、即発送いたします。
            </p>
        </div>
        <div class="item__info">
            <h2 class="item-info__title sub-title">商品の情報</h2>
            <dl class="product-info__list">
                <dt class="product-info__term">カテゴリー</dt>
                <!-- あとでダミーデータが入る -->
                <dd class="product-info__desc">洋服</dd>
                <dd class="product-info__desc">メンズ</dd>
            </dl>
            <dl class="product-info__list">
                <dt class="product-info__term">商品の状態</dt>
                <dd class="product-info__desc">良好</dd>
            </dl>
        </div>
        <div class="item__comment">
            <h2 class="item-comment__title">コメント
                <span class="item__comment-count">(1)</span>
            </h2>
            <div class="item-comment__inner">
                <div class="item-comment__user">
                    <img src="" alt="" class="comment-user__image">
                    <p class="comment-user__name">※ユーザー名</p>
                </div>
                <p class="comment__content">※こちらにコメントが入ります。</p>
            </div>
            <h2 class="item-comment__sub-title">商品へのコメント</h2>
            <form action="" method="post" class="comment-form">
                @csrf
                <textarea name="" cols="30" rows="5" class="comment__description" placeholder="※コメントを入力してください"></textarea>
                <div class="comment__button">
                    <button class="comment-button__submit common-btn">コメントを送信する</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
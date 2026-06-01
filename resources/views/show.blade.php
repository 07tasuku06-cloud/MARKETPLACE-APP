@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
@endsection

@section('content')

<div class="product-detail">

    <!-- 左：商品画像 -->
    <div class="product-detail__left">
        <img
            class="product-detail__image"
            src="{{ $product->image }}"
            alt="商品画像">
    </div>

    <!-- 右：商品情報 -->
    <div class="product-detail__right">

        <!-- 商品名 -->
        <h1 class="product-detail__title">
            {{ $product->name }}
        </h1>

        <!-- ブランド -->
        <p class="product-detail__brand">
            {{ $product->brand }}
        </p>

        <!-- 価格 -->
        <p class="product-detail__price">
            ¥{{ number_format($product->price) }}
            <span>(税込)</span>
        </p>

        <!-- いいね・コメント -->
        <div class="product-detail__icons">

            <!-- いいね -->
            <div class="icon-group">

                <form method="POST" action="/like/{{ $product->id }}">
                    @csrf

                    <button type="submit" class="like-button" style="background:none;border:none;cursor:pointer;">

                        @if($isLiked)
                        <img
                            class="like-icon"
                            src="{{ asset('images/ハートロゴ_ピンク.png') }}"
                            alt="liked">
                        @else
                        <img
                            class="like-icon"
                            src="{{ asset('images/ハートロゴ_デフォルト.png') }}"
                            alt="not liked">
                        @endif

                    </button>
                </form>

                <p>{{ $likeCount }}</p>

            </div>

            <!-- コメント数 -->
            <div class="icon-group">

                <img
                    class="comment-icon"
                    src="{{ asset('images/ふきだしロゴ.png') }}"
                    alt="comments">

                <p>{{ $product->comments->count() }}</p>

            </div>

        </div>

        <!-- 購入ボタン -->
        <form method="GET" action="/purchase/{{ $product->id }}">
            <button type="submit" class="purchase-button">
                購入手続きへ
            </button>
        </form>

        <!-- 商品説明 -->
        <div class="product-section">

            <h2>商品説明</h2>

            <div class="product-description">

                <p>カラー：{{ $product->color ?? '未設定' }}</p>

                <p>
                    {{ $product->description ?? '商品説明はありません' }}
                </p>

            </div>

        </div>

        <!-- 商品情報 -->
        <div class="product-section">

            <h2>商品の情報</h2>

            <div class="product-info-row">

                <p class="label">カテゴリー</p>

                <div class="category-list">

                    @foreach ($product->categories as $category)
                    <span class="category-tag">
                        {{ $category->name }}
                    </span>
                    @endforeach

                </div>

            </div>

            <div class="product-info-row">

                <p class="label">商品の状態</p>

                <p>{{ $product->condition }}</p>

            </div>

        </div>

        <!-- コメント -->
        <div class="comment-section">

            <h2 class="comment-title">
                コメント({{ $product->comments->count() }})
            </h2>

            <!-- コメント一覧 -->
            @foreach ($product->comments as $comment)

            <div class="comment">

                <div class="comment__header">

                    <div class="comment__avatar"></div>

                    <p class="comment__user">
                        {{ $comment->user->name }}
                    </p>

                </div>

                <div class="comment__body">
                    {{ $comment->comment }}
                </div>

            </div>

            @endforeach

            <!-- コメント投稿 -->
            <form
                class="comment-form"
                method="POST"
                action="/comment/{{ $product->id }}">

                @csrf

                <h3>商品へのコメント</h3>

                <textarea name="comment"></textarea>
                @error('comment')
                <p style="color:red;">
                    {{ $message }}
                </p>
                @enderror

                <button type="submit" class="comment-button">
                    コメントを送信する
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
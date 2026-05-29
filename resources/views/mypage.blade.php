@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="mypage">

    <div class="mypage-profile">

        <!-- 左 -->
        <div class="mypage-profile__left">

            <div class="mypage-profile__image">

                @if($user->profile_image)

                <img
                    src="{{ asset('storage/' . $user->profile_image) }}"
                    alt="">

                @endif

            </div>

            <h1 class="mypage-profile__name">
                {{ $user->name }}
            </h1>

        </div>

        <!-- 右 -->
        <a
            href="/mypage/profile"
            class="profile-edit-button">

            プロフィールを編集

        </a>

    </div>

    <!-- タブ -->
    <div class="product-tabs">

        <!-- 出品 -->
        <a
            href="/mypage?page=sell"
            class="{{ request('page') !== 'buy' ? 'active' : '' }}">

            出品した商品

        </a>

        <!-- 購入 -->
        <a
            href="/mypage?page=buy"
            class="{{ request('page') === 'buy' ? 'active' : '' }}">

            購入した商品

        </a>

    </div>

    <!-- 商品一覧 -->
    <div class="product-list">

        @foreach ($products as $product)

        <div class="product-card">

            <img src="{{ $product->image }}" alt="">

            <p>{{ $product->name }}</p>

        </div>

        @endforeach

    </div>

</div>

@endsection
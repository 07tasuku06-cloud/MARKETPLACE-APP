@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

<div class="purchase">

    <div class="purchase__container">

        <!-- 左カラム -->
        <div class="purchase__left">

            <div class="purchase__product">

                @if(Str::startsWith($product->image, '/images/'))

                <img
                    class="purchase__image"
                    src="{{ asset($product->image) }}"
                    alt="{{ $product->name }}">

                @else

                <img
                    class="purchase__image"
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}">

                @endif

                <div class="purchase__info">

                    <h1 class="purchase__name">
                        {{ $product->name }}
                    </h1>

                    <p class="purchase__price">
                        ¥{{ number_format($product->price) }}
                    </p>

                </div>

            </div>

            <!-- フォーム開始 -->
            <form method="POST" action="/purchase/{{ $product->id }}">
                @csrf

                <!-- 支払い方法 -->
                <div class="purchase__section">

                    <h2 class="purchase__title">支払い方法</h2>

                    <select class="purchase__select" name="payment_method">
                        <option value="">選択してください</option>
                        <option value="card" @selected(old('payment_method')=='card' )>
                            カード支払い
                        </option>
                        <option value="convenience" @selected(old('payment_method')=='convenience' )>
                            コンビニ支払い
                        </option>
                    </select>

                    @error('payment_method')
                    <p class="purchase__error">{{ $message }}</p>
                    @enderror

                </div>

                <!-- 配送先（ここが修正ポイント） -->
                <div class="purchase__section">

                    <div class="purchase__address-header">

                        <h2 class="purchase__title">配送先</h2>

                        <a href="/purchase/address/{{ $product->id }}" class="purchase__link">
                            変更する
                        </a>

                    </div>

                    <div class="purchase__address-body">

                        <p>
                            〒{{ session('purchase_postal_code', $user->postal_code) }}
                        </p>

                        <p>
                            {{ session('purchase_address', $user->address) }}
                        </p>

                        <p>
                            {{ session('purchase_building', $user->building_name) }}
                        </p>

                    </div>

                </div>

                <!-- hidden（Order保存用） -->
                <input type="hidden" name="postal_code"
                    value="{{ session('purchase_postal_code', $user->postal_code) }}">

                <input type="hidden" name="address"
                    value="{{ session('purchase_address', $user->address) }}">

                <input type="hidden" name="building"
                    value="{{ session('purchase_building', $user->building_name) }}">

        </div>

        <!-- 右カラム -->
        <div class="purchase__right">

            <div class="purchase__summary">

                <div class="purchase__row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($product->price) }}</span>
                </div>

                <div class="purchase__row">
                    <span>支払い方法</span>
                    <span id="payment-display">未選択</span>
                </div>

            </div>

            <button type="submit" class="purchase__button">
                購入する
            </button>

        </div>

        </form>

    </div>

</div>

<!-- JS -->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const select = document.querySelector('[name="payment_method"]');
        const display = document.getElementById('payment-display');

        const map = {
            card: 'カード支払い',
            convenience: 'コンビニ支払い'
        };

        function sync() {
            display.textContent = map[select.value] ?? '未選択';
        }

        select.addEventListener('change', sync);
        sync();

    });
</script>

@endsection
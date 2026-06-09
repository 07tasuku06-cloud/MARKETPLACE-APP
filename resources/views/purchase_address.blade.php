@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<div class="auth">

    <h1 class="auth__title">
        住所の変更
    </h1>

    <div class="auth__container">

        <form
            class="auth__form"
            method="POST"
            action="/purchase/address/{{ $product->id }}">

            @csrf

            <!-- 郵便番号 -->
            <label class="auth__label">
                郵便番号
            </label>

            <input
                class="auth__input"
                type="text"
                name="postal_code"
                value="{{ old('postal_code', $user->postal_code) }}">

            @error('postal_code')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <!-- 住所 -->
            <label class="auth__label">
                住所
            </label>

            <input
                class="auth__input"
                type="text"
                name="address"
                value="{{ old('address', $user->address) }}">

            @error('address')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <!-- 建物名 -->
            <label class="auth__label">
                建物名
            </label>

            <input
                class="auth__input"
                type="text"
                name="building_name"
                value="{{ old('building_name', $user->building_name) }}">

            @error('building_name')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <button
                class="auth__button"
                type="submit">
                更新する
            </button>

        </form>

    </div>

</div>

@endsection
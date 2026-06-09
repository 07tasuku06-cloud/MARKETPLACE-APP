@php
use Illuminate\Support\Facades\Auth;
@endphp

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<div class="auth">
    <h1 class="auth__title">プロフィール設定</h1>

    <div class="auth__container">

        <form
            class="auth__form"
            method="POST"
            action="/mypage/profile"
            enctype="multipart/form-data">

            @csrf

            <!-- プロフィール画像 -->
            <div class="profile__image-area">

                <div class="profile__image-preview">

                    <img
                        id="preview"
                        @if(Auth::user()->profile_image)
                    src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                    @endif
                    alt=""
                    class="{{ Auth::user()->profile_image ? '' : 'hidden-image' }}">
                </div>

                <label class="profile__image-label">
                    画像を選択する

                    <input
                        type="file"
                        name="profile_image"
                        accept="image/*"
                        hidden
                        onchange="previewImage(event)">
                </label>

            </div>
            @error('profile_image')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <!-- ユーザー名 -->
            <label class="auth__label">ユーザー名</label>
            <input
                class="auth__input"
                type="text"
                name="name"
                value="{{ old('name', Auth::user()->name) }}">

            @error('name')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <!-- 郵便番号 -->
            <label class="auth__label">郵便番号</label>
            <input
                class="auth__input"
                type="text"
                name="postal_code"
                value="{{ old('postal_code', Auth::user()->postal_code) }}">

            @error('postal_code')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <!-- 住所 -->
            <label class="auth__label">住所</label>
            <input
                class="auth__input"
                type="text"
                name="address"
                value="{{ old('address', Auth::user()->address) }}">

            @error('address')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <!-- 建物名 -->
            <label class="auth__label">建物名</label>
            <input
                class="auth__input"
                type="text"
                name="building_name"
                value="{{ old('building_name', Auth::user()->building_name) }}">

            @error('building_name')
            <p class="error-message">{{ $message }}</p>
            @enderror

            <button
                class="auth__button"
                type="submit">
                保存
            </button>

        </form>

    </div>

</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();

        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.src = e.target.result;
            preview.classList.remove('hidden-image');
        };

        reader.readAsDataURL(file);
    }
</script>

@endsection
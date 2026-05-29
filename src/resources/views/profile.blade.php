@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<div class="auth">
    <h1 class="auth__title">プロフィール設定</h1>

    <div class="auth__container">

        <form class="auth__form" method="POST" action="/mypage/profile" enctype="multipart/form-data">
            @csrf
            <!-- プロフィール画像 -->
            <div class="profile__image-area">

                <div class="profile__image-preview">

                    @if(Auth::user()->profile_image)

                    <img
                        id="preview"
                        src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                        alt="">

                    @else

                    <img
                        id="preview"
                        src=""
                        alt=""
                        style="display:none;">

                    @endif

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

            <!-- ユーザー名 -->
            <label class="auth__label">ユーザー名</label>

            <input
                class="auth__input"
                type="text"
                name="name"
                value="{{ old('name', Auth::user()->name) }}">

            <!-- 郵便番号 -->
            <label class="auth__label">郵便番号</label>

            <input
                class="auth__input"
                type="text"
                name="postal_code">

            <!-- 住所 -->
            <label class="auth__label">住所</label>

            <input
                class="auth__input"
                type="text"
                name="address">

            <!-- 建物名 -->
            <label class="auth__label">建物名</label>

            <input
                class="auth__input"
                type="text"
                name="building_name">

            <button class="auth__button" type="submit">
                保存
            </button>

        </form>

    </div>
</div>

<!-- 画像プレビュー -->
<script>
    function previewImage(event) {

        const reader = new FileReader();

        reader.onload = function() {

            document.getElementById('preview').src = reader.result;
        };

        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
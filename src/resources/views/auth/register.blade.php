@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<div class="auth">
    <h1 class="auth__title">会員登録</h1>

    <div class="auth__container">

        <form class="auth__form" method="POST" action="/register">
            @csrf

            <!-- ユーザー名 -->
            <label class="auth__label">ユーザー名</label>
            <input class="auth__input" type="text" name="name">
            @error('name')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <!-- メールアドレス -->
            <label class="auth__label">メールアドレス</label>
            <input class="auth__input" type="email" name="email">
            @error('email')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <!-- パスワード -->
            <label class="auth__label">パスワード</label>
            <input class="auth__input" type="password" name="password">
            @error('password')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <!-- 確認用パスワード -->
            <label class="auth__label">確認用パスワード</label>
            <input class="auth__input" type="password" name="password_confirmation">
            @error('password_confirmation')
            <p class="auth__error">{{ $message }}</p>
            @enderror

            <button class="auth__button" type="submit">登録</button>
        </form>

        <a class="auth__link" href="/login">ログインはこちら</a>

    </div>
</div>

@endsection
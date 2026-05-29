@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<div class="auth">
    <h1 class="auth__title">ログイン</h1>

    <div class="auth__container">

        <form class="auth__form" method="POST" action="/login">
            @csrf

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

            <button class="auth__button" type="submit">ログイン</button>
        </form>

        <!-- ログイン失敗（認証エラー） -->
        @if ($errors->has('email') && !$errors->has('password'))
        <p class="auth__error">
            {{ $errors->first('email') }}
        </p>
        @endif

        <a class="auth__link" href="/register">会員登録はこちら</a>

    </div>
</div>

@endsection
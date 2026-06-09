@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')

<div class="verify-email">

    <div class="verify-email__container">

        <p class="verify-email__text">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <button type="button" class="verify-email__button">
            認証はこちらから
        </button>

        <form
            method="POST"
            action="{{ route('verification.send') }}"
            class="verify-email__resend-form">

            @csrf

            <button
                type="submit"
                class="verify-email__resend">
                認証メールを再送する
            </button>

        </form>

    </div>

</div>

@endsection
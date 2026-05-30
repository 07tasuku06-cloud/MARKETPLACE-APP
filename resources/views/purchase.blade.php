@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<h1>購入画面</h1>

<p>{{ $product->name }}</p>

<p>¥{{ number_format($product->price) }}</p>

@endsection
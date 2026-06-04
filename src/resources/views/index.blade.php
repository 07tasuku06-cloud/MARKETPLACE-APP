@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="product-tabs">

    <!-- おすすめ -->
    <a
        href="/?keyword={{ request('keyword') }}"
        class="{{ request('tab') !== 'mylist' ? 'active' : '' }}">
        おすすめ
    </a>

    <!-- マイリスト -->
    <a
        href="/?tab=mylist&keyword={{ request('keyword') }}"
        class="{{ request('tab') === 'mylist' ? 'active' : '' }}">
        マイリスト
    </a>

</div>

<div class="product-list">

    @foreach ($products as $product)

    <a
        href="/item/{{ $product->id }}"
        class="product-card">

        @if(Str::startsWith($product->image, '/images/'))

        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">

        @else

        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">

        @endif

        <p>{{ $product->name }}</p>

        @if($product->is_sold)
        <p class="sold">SOLD</p>
        @endif

    </a>

    @endforeach

</div>

@endsection
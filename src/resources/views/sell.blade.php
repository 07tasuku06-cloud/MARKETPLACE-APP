@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')

<div class="sell">

    <h1 class="sell__title">商品の出品</h1>

    <div class="sell__container">

        <form action="/sell" method="POST" enctype="multipart/form-data" class="sell__form">
            @csrf

            {{-- 商品画像 --}}
            <div class="sell__section">

                <label class="sell__label">
                    商品画像
                </label>

                <div class="sell__image-area">

                    <img
                        id="image-preview"
                        class="sell__image-preview"
                        style="display:none;">

                    <input
                        type="file"
                        id="image"
                        name="image"
                        class="sell__image-input"
                        accept="image/*">

                    <div
                        id="image-button"
                        class="sell__image-button">
                        画像を選択する
                    </div>

                </div>

                @error('image')
                <p class="sell__error">{{ $message }}</p>
                @enderror

            </div>

            {{-- 商品詳細 --}}
            <div class="sell__section">

                <h2 class="sell__heading">商品の詳細</h2>

                <label class="sell__label">カテゴリー</label>

                <div class="sell__categories">

                    @foreach($categories as $category)
                    <label class="sell__category">
                        <input
                            type="checkbox"
                            name="category_ids[]"
                            value="{{ $category->id }}"
                            class="sell__category-input"
                            {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>

                        <span class="sell__category-text">
                            {{ $category->name }}
                        </span>
                    </label>
                    @endforeach

                </div>

                @error('category_ids')
                <p class="sell__error">{{ $message }}</p>
                @enderror

                <label class="sell__label">商品の状態</label>

                <select name="condition" class="sell__select">


                    <option value="" {{ old('condition') == null ? 'selected' : '' }}>
                        選択してください
                    </option>

                    <option value="良好" {{ old('condition') == '良好' ? 'selected' : '' }}>
                        良好
                    </option>

                    <option value="目立った傷や汚れなし" {{ old('condition') == '目立った傷や汚れなし' ? 'selected' : '' }}>
                        目立った傷や汚れなし
                    </option>

                    <option value="やや傷や汚れあり" {{ old('condition') == 'やや傷や汚れあり' ? 'selected' : '' }}>
                        やや傷や汚れあり
                    </option>

                    <option value="状態が悪い" {{ old('condition') == '状態が悪い' ? 'selected' : '' }}>
                        状態が悪い
                    </option>

                </select>

                @error('condition')
                <p class="sell__error">{{ $message }}</p>
                @enderror

            </div>

            {{-- 商品名と説明 --}}
            <div class="sell__section">

                <h2 class="sell__heading">商品名と説明</h2>

                <label class="sell__label">商品名</label>
                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    class="sell__input">

                @error('name')
                <p class="sell__error">{{ $message }}</p>
                @enderror

                <label class="sell__label">ブランド名</label>
                <input
                    type="text"
                    name="brand"
                    value="{{ old('brand') }}"
                    class="sell__input">

                <label class="sell__label">商品の説明</label>
                <textarea
                    name="description"
                    class="sell__textarea">{{ old('description') }}</textarea>

                @error('description')
                <p class="sell__error">{{ $message }}</p>
                @enderror

                <label class="sell__label">販売価格</label>
                <input
                    type="number"
                    name="price"
                    value="{{ old('price') }}"
                    class="sell__input">

                @error('price')
                <p class="sell__error">{{ $message }}</p>
                @enderror

            </div>

            <button type="submit" class="sell__button">
                出品する
            </button>

        </form>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const input = document.getElementById('image');
        const preview = document.getElementById('image-preview');
        const button = document.getElementById('image-button');

        // ボタンでファイル選択
        button.addEventListener('click', function() {
            input.click();
        });

        // プレビュー表示
        input.addEventListener('change', function(e) {

            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(event) {

                preview.src = event.target.result;
                preview.style.display = 'block';

                // ボタン非表示
                button.style.display = 'none';
            };

            reader.readAsDataURL(file);
        });

        // 画像クリックで再選択
        preview.addEventListener('click', function() {
            input.click();
        });

    });
</script>

@endsection
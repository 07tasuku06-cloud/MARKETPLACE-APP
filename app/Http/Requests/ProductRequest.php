<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required'],
            'description' => ['required', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png'],
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
            'condition' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください',

            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',

            'image.required' => '商品画像を選択してください',
            'image.image' => '画像ファイルを選択してください',
            'image.mimes' => '商品画像はjpegまたはpng形式で選択してください',

            'category_ids.required' => 'カテゴリーを選択してください',

            'condition.required' => '商品の状態を選択してください',

            'price.required' => '販売価格を入力してください',
            'price.numeric' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class PurchaseController extends Controller
{
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('purchase', compact('product'));
    }

    public function store(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // すでに売れていたら防止
        if ($product->is_sold) {
            return redirect('/')->with('error', 'この商品は売り切れです');
        }

        $user = Auth::user();

        // 注文作成
        Order::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'payment_method' => $request->payment_method ?? 'card',
            'postal_code' => $request->postal_code ?? $user->postal_code,
            'address' => $request->address ?? $user->address,
            'building' => $request->building ?? $user->building_name,
            'price' => $product->price,
        ]);

        // 売却済みにする
        $product->update([
            'is_sold' => true
        ]);

        return redirect('/')->with('success', '購入が完了しました');
    }
}

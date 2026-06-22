<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    /**
     * 購入画面表示
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        return view('purchase', compact('product', 'user'));
    }

    /**
     * 通常購入処理
     */
    public function store(PurchaseRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_sold) {
            return redirect('/')->with('error', 'この商品は売り切れです');
        }

        $user = Auth::user();

        DB::transaction(function () use ($request, $product, $user) {
            Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'payment_method' => $request->payment_method,
                'postal_code' => $request->postal_code ?? $user->postal_code,
                'address' => $request->address ?? $user->address,
                'building' => $request->building ?? $user->building_name,
                'price' => $product->price,
            ]);

            $product->update([
                'is_sold' => true,
            ]);
        });

        return redirect('/mypage?page=buy')->with('success', '購入が完了しました');
    }

    /**
     * 住所変更画面表示
     */
    public function showAddressForm($product_id)
    {
        $product = Product::findOrFail($product_id);
        $user = Auth::user();

        return view('purchase_address', compact('product', 'user'));
    }

    /**
     * 住所変更処理
     */
    public function updateAddress(AddressRequest $request, $product_id)
    {
        session([
            'purchase_postal_code' => $request->postal_code,
            'purchase_address' => $request->address,
            'purchase_building' => $request->building,
        ]);

        return redirect('/purchase/' . $product_id);
    }

    /**
     * Stripe決済画面へ移動
     */
    public function checkout($id)
    {
        $product = Product::findOrFail($id);

        if ($product->is_sold) {
            return redirect('/')->with('error', 'この商品は売り切れです');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => url('/purchase/success/' . $id),
            'cancel_url' => url('/purchase/' . $id),
        ]);

        return redirect($session->url);
    }

    /**
     * Stripe決済成功後の購入確定処理
     */
    public function success($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        if ($product->is_sold) {
            return redirect('/')->with('error', 'この商品は売り切れです');
        }

        DB::transaction(function () use ($product, $user) {
            Order::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'payment_method' => 'card',
                'postal_code' => session('purchase_postal_code') ?? $user->postal_code,
                'address' => session('purchase_address') ?? $user->address,
                'building' => session('purchase_building') ?? $user->building_name,
                'price' => $product->price,
            ]);

            $product->update([
                'is_sold' => true,
            ]);
        });

        session()->forget([
            'purchase_postal_code',
            'purchase_address',
            'purchase_building',
        ]);

        return redirect('/mypage?page=buy')->with('success', '購入が完了しました');
    }
}

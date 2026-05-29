<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ProfileController extends Controller
{
    /**
     * プロフィール入力画面
     */
    public function edit()
    {
        return view('profile');
    }

    /**
     * プロフィール保存処理
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = [
            'name' => $request->name,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building_name' => $request->building_name,
            'is_profile_completed' => true,
        ];

        // 画像保存
        if ($request->hasFile('profile_image')) {

            $path = $request->file('profile_image')
                ->store('profiles', 'public');

            $data['profile_image'] = $path;
        }

        $user->update($data);

        return redirect('/');
    }

    public function mypage(Request $request)
    {
        $user = auth()->user();

        $page = $request->page;

        // 出品商品
        if ($page === 'sell' || $page === null) {

            $products = Product::where('user_id', $user->id)
                ->get();
        }

        // 購入商品（未実装）
        if ($page === 'buy') {

            $products = collect();
        }

        return view('mypage', compact('user', 'products', 'page'));
    }
}

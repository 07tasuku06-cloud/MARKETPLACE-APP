<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

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
    public function update(ProfileRequest $request)
    {
        $validated = $request->validated();

        $user = Auth::user();

        $data = $validated;
        $data['is_profile_completed'] = true;

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')
                ->store('profiles', 'public');
        }

        $user->update($data);

        return redirect('/mypage');
    }
    /**
     * マイページ
     */
    public function mypage(Request $request)
    {
        $user = auth()->user();
        $page = $request->page;

        if ($page === 'sell' || $page === null) {
            $products = Product::where('user_id', $user->id)->get();
        }

        if ($page === 'buy') {
            $products = Order::with('product')
                ->where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('mypage', compact('user', 'products', 'page'));
    }
}

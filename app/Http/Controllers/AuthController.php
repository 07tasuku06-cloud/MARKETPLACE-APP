<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * 会員登録
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        // ユーザー作成
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_profile_completed' => false, // ★追加（重要）
        ]);

        Auth::login($user);

        return redirect('/mypage/profile');
    }

    /**
     * ログイン
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        if (!Auth::attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ])) {
            return back()->withErrors([
                'email' => 'ログイン情報が登録されていません',
            ])->withInput();
        }

        $request->session()->regenerate();

        return redirect('/');
    }

    /**
     * ログアウト
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/login');
    }
}

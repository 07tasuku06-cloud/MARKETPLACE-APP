<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
/*
|--------------------------------------------------------------------------
| 商品関連（誰でもアクセス可能）
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'index']);
Route::get('/item/{id}', [ProductController::class, 'show']);

/*
|--------------------------------------------------------------------------
| ゲスト専用（未ログイン）
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {



    // ログイン画面
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    // ログイン処理
    Route::post('/login', [AuthController::class, 'login']);

    // 会員登録画面
    Route::get('/register', function () {
        return view('auth.register');
    });

    // 会員登録処理
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| 認証必須
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {


    // 購入画面
    Route::get('/purchase/{id}', [PurchaseController::class, 'show']);

    // 購入処理
    Route::post('/purchase/{id}', [PurchaseController::class, 'store']);

    //配送先変更
    Route::get(
        '/purchase/address/{product_id}',
        [PurchaseController::class, 'showAddressForm']
    );

    Route::post(
        '/purchase/address/{product_id}',
        [PurchaseController::class, 'updateAddress']
    );

    // ログアウト
    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | いいね・コメント
    |--------------------------------------------------------------------------
    */

    Route::post('/like/{product_id}', [LikeController::class, 'store']);
    Route::post('/comment/{product_id}', [CommentController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | マイページ
    |--------------------------------------------------------------------------
    */

    Route::get('/mypage', [ProfileController::class, 'mypage']);

    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
});

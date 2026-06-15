<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| メール認証（FN012 / FN013）
|--------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/mypage/profile');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', '認証メールを送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| ログイン済みユーザー
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ログアウト
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);

    /*
    |--------------------------------------------------------------------------
    | マイページ
    |--------------------------------------------------------------------------
    */

    Route::get('/mypage', [ProfileController::class, 'mypage']);

    Route::get('/mypage/profile', [ProfileController::class, 'edit']);
    Route::post('/mypage/profile', [ProfileController::class, 'update']);
});

/*
|--------------------------------------------------------------------------
| メール認証済みユーザーのみ
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 購入
    |--------------------------------------------------------------------------
    */

    Route::get('/purchase/{id}', [PurchaseController::class, 'show']);
    Route::post('/purchase/{id}', [PurchaseController::class, 'store']);

    Route::get('/purchase/address/{product_id}', [PurchaseController::class, 'showAddressForm']);
    Route::post('/purchase/address/{product_id}', [PurchaseController::class, 'updateAddress']);

    /*
    |--------------------------------------------------------------------------
    | いいね・コメント
    |--------------------------------------------------------------------------
    */

    Route::post('/like/{product_id}', [LikeController::class, 'store']);
    Route::post('/comment/{product_id}', [CommentController::class, 'store']);

    /*
    |--------------------------------------------------------------------------
    | 出品
    |--------------------------------------------------------------------------
    */

    Route::get('/sell', [ProductController::class, 'create']);
    Route::post('/sell', [ProductController::class, 'store']);
});

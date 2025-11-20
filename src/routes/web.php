<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{id}', [ItemController::class, 'detail']);

/* 会員のみプロフィールの作成、編集ができる */
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mypage/profile', [ProfileController::class, 'create']);
    Route::post('/mypage/profile', [ProfileController::class, 'store']);
});

/* メール認証 */
Route::middleware('auth')->group(function () {

    Route::get('/email/verify', fn() => view('auth.verify-email'))
        ->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/mypage/profile');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');
});

/* 会員登録済みかつ、プロフィール作成ユーザーが表示できる画面 */
Route::middleware('auth', 'verified', 'profile.set')->group(function () {
    Route::get('/mypage', [ProfileController::class, 'mypage']);
    Route::get('/sell', [ItemController::class, 'create']);
    Route::post('/sell', [ItemController::class, 'store']);
    Route::post('/item/{id}/comment', [CommentController::class, 'comment']);
    Route::post('/item/{id}/like', [LikeController::class, 'like']);
    Route::get('/purchase/address/{id}', [DeliveryController::class, 'create']);
    Route::post('/purchase/address/{id}', [DeliveryController::class, 'store']);
    Route::get('/purchase/{id}', [CheckoutController::class, 'showCheckout'])
        ->name('purchase.show');
    Route::post('/purchase/{id}', [CheckoutController::class, 'purchase'])
        ->name('purchase');
    Route::get('/pay/konbini/{id}', [CheckoutController::class, 'purchaseKonbini'])
        ->name('pay.konbini');
    Route::get('/pay/card/{id}', [CheckoutController::class, 'purchaseCard'])
        ->name('stripe.card');
});

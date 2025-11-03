<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

// Route::get('/sell', function () {
//     return view('sell');
// });

Route::get('/',[ItemController::class,'indexView']);
Route::get('/item/{id}',[ItemController::class,'detailView']);
Route::get('//search',[ItemController::class,'search']);

// ログイン後の遷移制御
Route::get('/after-login', [ProfileController::class,'redirectToProfileSetup'])
    ->middleware('auth');

Route::middleware('auth')->group(function (){
    Route::get('/mypage/profile',[ProfileController::class,'profileView']);
    Route::post('/mypage/profile',[ProfileController::class,'profileCreate']);
    Route::get('/sell',[ItemController::class,'createView']);
    Route::post('/sell',[ItemController::class,'create']);
    Route::post('/item/{id}/comment',[CommentController::class,'comment']);
});
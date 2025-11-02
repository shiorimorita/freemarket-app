<?php

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

Route::get('/', [ProfileController::class,'afterLogin']);

Route::middleware('auth')->group(function (){
    Route::get('/mypage/profile',[ProfileController::class,'profileView']);
    Route::post('/mypage/profile',[ProfileController::class,'profileCreate']);

    Route::get('/sell', function () {
        return view('sell');
    });
    Route::post('/sell',[ItemController::class,'create']);
    
});
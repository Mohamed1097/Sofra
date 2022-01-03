<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
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

Auth::routes();
Route::prefix('admin')->name('admin.')->group(function(){
    Route::middleware(['guest:web','PreventBackHistory'])->group(function(){
        Route::view('/login','auth.login')->name('login');
        Route::post('/check',[UserController::class,'check'])->name('check');
  });
  Route::middleware(['auth','PreventBackHistory'])->group(function () 
  {
    Route::get('/',[HomeController::class,'index'])->name('home');
    Route::post('/logout',[UserController::class,'logout'])->name('logout');
    Route::resource('clients',ClientController::class);
    Route::resource('restaurants',RestaurantController::class);
  });
});

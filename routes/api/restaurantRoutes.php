<?php

use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\Restaurant\AuthController;
use App\Http\Controllers\Api\restaurant\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api/Restaurant'],function(){

    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('forget-password',[AuthController::class,'forgetPassword']);
    Route::post('reset-password',[AuthController::class,'resetPassword']);


    Route::group(['middleware'=>'auth:api-restaurant'],function(){
        Route::get('comments',[RestaurantController::class,'getComments']);

        Route::post('add-meal',[RestaurantController::class,'addMeal']);
        Route::get('meals',[RestaurantController::class,'getMeals']);
        Route::get('meal',[RestaurantController::class,'getMeal']);
        Route::post('edit-meal',[RestaurantController::class,'editMeal']);
        Route::post('delete-meal',[RestaurantController::class,'deleteMeal']);

        Route::post('add-offer',[RestaurantController::class,'addOffer']);
        Route::get('offers',[RestaurantController::class,'getOffers']);
        Route::post('delete-offer',[RestaurantController::class,'deleteOffer']);
        Route::get('offer',[RestaurantController::class,'getOffer']);
        Route::post('edit-offer',[RestaurantController::class,'editOffer']);

        Route::get('profile',[AuthController::class,'getProfile']);
        Route::post('edit-profile',[AuthController::class,'editProfile']);

        Route::get('order',[RestaurantController::class,'getOrder']);
        Route::get('previous-orders',[RestaurantController::class,'previousOrders']);
        Route::get('current-orders',[RestaurantController::class,'currentOrders']);
        Route::get('new-orders',[RestaurantController::class,'newOrders']);
        Route::post('accept-order',[RestaurantController::class,'acceptOrder']);
        Route::post('reject-order',[RestaurantController::class,'rejectOrder']);
        Route::post('delivery-order',[RestaurantController::class,'deliveryOrder']);

        Route::get('notifications',[MainController::class,'getNotifications']);
        Route::post('read-notification',[MainController::class,'readNotification']);
        Route::post('read-all-notifications',[MainController::class,'readAllNotifications']);

        Route::get('payment',[RestaurantController::class,'payment']);

        Route::post('register-token',[MainController::class,'registerToken']);
        Route::post('remove-token',[MainController::class,'removeToken']);

        Route::post('send-message',[MainController::class,'sendMessage']);

        Route::get('app-commission',[RestaurantController::class,'getAppCommission']);
    });
});
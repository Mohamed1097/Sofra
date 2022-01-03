<?php

use App\Http\Controllers\Api\Client\AuthController;
use App\Http\Controllers\Api\Client\ClientController;
use App\Http\Controllers\Api\MainController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace'=>'Api/Client'],function(){
    
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);
    Route::post('forget-password',[AuthController::class,'forgetPassword']);
    Route::post('reset-password',[AuthController::class,'resetPassword']);



    Route::get('meals',[ClientController::class,'getMeals']);
    Route::get('meal',[ClientController::class,'getMeal']);

    Route::get('comments',[ClientController::class,'getComments']);

    Route::get('restaurants',[ClientController::class,'getRestaurants']);

    Route::get('offers',[ClientController::class,'getOffers']);

    Route::get('restaurant-info',[ClientController::class,'getRestaurant']);

    Route::group(['middleware'=>'auth:api-client'],function(){ 
        Route::post('add-comment',[ClientController::class,'addComment']);

        
        Route::get('profile',[AuthController::class,'getProfile']);
        Route::post('edit-profile',[AuthController::class,'editProfile']);

        Route::post('add-order',[ClientController::class,'addOrder']);
        Route::get('previous-orders',[ClientController::class,'previousOrders']);
        Route::get('current-orders',[ClientController::class,'currentOrders']);
        Route::get('order',[ClientController::class,'getOrder']);
        Route::post('cancel-order',[ClientController::class,'cancelOrder']);

        Route::get('notifications',[MainController::class,'getNotifications']);
        Route::post('read-notification',[MainController::class,'readNotification']);
        Route::post('read-all-notifications',[MainController::class,'readAllNotifications']);

        Route::post('register-token',[MainController::class,'registerToken']);
        Route::post('remove-token',[MainController::class,'removeToken']);

        Route::post('send-message',[MainController::class,'sendMessage']);
    });

});



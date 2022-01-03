<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MainController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::prefix('v1')->group(function(){
    Route::get('cities',[MainController::class,'getCities']);
    Route::get('neighborhoods',[MainController::class,'getNeighborhoods']);
    Route::get('food-categories',[MainController::class,'getFoodCategories']);
    Route::get('settings',[MainController::class,'getSettings']);
    Route::get('test',[MainController::class,'test']);
});
Route::prefix('/restaurant/v1')->group(__DIR__.'/api/restaurantRoutes.php');
Route::prefix('/client/v1')->group(__DIR__.'/api/clientRoutes.php');

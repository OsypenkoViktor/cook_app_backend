<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Policies\ProductPolicy;
use App\Http\Controllers\CookProcessController;

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

 Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware("auth")->group(function (){
    Route::apiResource("products",ProductController::class);

    Route::prefix("products")->group(function (){
        Route::get("{product}/{cookProcess}",[CookProcessController::class,"show"]);
        Route::post("{product}/{cookProcess}",[CookProcessController::class,"store"])->middleware('can:create,cookProcess');
        Route::delete("{product}/{cookProcess}",[CookProcessController::class,"destroy"])->middleware('can:delete,cookProcess');
        Route::patch("{product}/{cookProcess}",[CookProcessController::class,"update"])->middleware('can:update,cookProcess');
    });
});


<?php

use App\Http\Controllers\DishController;
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
Route::middleware('auth')->prefix('api')->group(function (){
    //resource controller for products
    Route::apiResource("products",ProductController::class);
    //additional routes for cook processes
    Route::prefix("products")->group(function (){
        Route::get("{product}/{cookProcess}",[CookProcessController::class,"show"]);
        Route::post("/{product}/cookProcessCreate",[CookProcessController::class,"store"])->middleware('can:create,App\Models\CookProcess');
        Route::delete("{product}/{cookProcess}",[CookProcessController::class,"destroy"])->middleware('can:delete,cookProcess');
        Route::patch("{product}/{cookProcess}",[CookProcessController::class,"update"])->middleware('can:update,cookProcess');
    });
    Route::prefix('dishes')->group(function (){
        Route::get('/',[DishController::class,'showAll']);
        Route::post('/',[DishController::class,'store'])->middleware('can:create,App\Models\Dish');
        Route::delete('/{dish}',[DishController::class,'destroy'])->middleware('can:delete,dish');
        Route::get('/{dish}',[DishController::class,'show']);
        Route::patch('/{dish}',[DishController::class,'update'])->middleware('can:update,cookProcess');
        //assign product to dish
        Route::get('/{dish}/vote',[DishController::class,'voteDish'])->middleware('can:create,dish');
        Route::get('/{dish}/comments',[DishController::class,'getComments'])->middleware('can:create,dish');
        Route::delete('/{dish}/comments/{comment}',[DishController::class,'deleteComment'])->middleware('can:create,dish');
        Route::patch('/{dish}/comments/{comment}',[DishController::class,'updateComment'])->middleware('can:create,dish');
        Route::post('/{dish}/comments',[DishController::class,'createComment'])->middleware('can:create,App\Models\Comment');
        Route::post('/{dish}/{product}',[DishController::class,'addProduct'])->middleware('can:create,dish');
        //removing product from dish
        Route::delete('/{dish}/{product}',[DishController::class,'removeProduct'])->middleware('can:delete,dish');
        //handling comments

    });
});


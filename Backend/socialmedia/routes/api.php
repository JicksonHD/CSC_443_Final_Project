<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ImageController;


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

Route::group(["prefix"=>"v0.1"],function(){
    Route::group(["prefix"=>"weshare"],function(){
        Route::controller(AuthController::class)->group(function(){
            Route::post("/login", "login");
            Route::post("/signup", "register");
        });
        Route::group(["prefix"=>"images"],function(){
            Route::controller(PostController::class)->group(function(){
                Route::post("/add", "addImage");
                Route::post("/delete", "deleteImage");
                Route::get("/","getImage");
            });
        });
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

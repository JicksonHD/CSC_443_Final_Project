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
        Route::group(["prefix"=>"users"],function(){
            Route::controller(AuthController::class)->group(function(){
                Route::post("/login", "login");
                Route::post("/signup", "register");
            });
        });
        Route::group(["prefix"=>"images"],function(){
            Route::controller(PostController::class)->group(function(){
                Route::post("/add", "addImage");
                Route::post("/delete", "deleteImage");
                Route::get("/","getImage");
            });
        });
        Route::group(["prefix"=>"likes"],function(){
            Route::controller(LikeController::class)->group(function(){
                Route::post("/add", "addLike");
                Route::post("/delete", "deleteLike");
                Route::get("/check/{image_id}", "checkLike");
                Route::get("/{image_id}","getLikes");
            });
        });
        Route::group(["prefix"=>"comments"],function(){
            Route::controller(CommentController::class)->group(function(){
                Route::post("/add", "addComment");
                Route::get("/delete/{comment_id}", "deleteComment");
                Route::get("/{post_id}","getComments");
            });
        });
        Route::group(["prefix"=>"hiddenImages"],function(){
            Route::controller(HiddenImageController::class)->group(function(){
                Route::post("/hide","hideImage");
                Route::post("/unhide","unhideImage");
            });
        });
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

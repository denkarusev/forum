<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    Route::group(['namespace' => 'Auth'], function () {
        Route::post('register', 'RegisterController');
        Route::post('login', 'LoginController');
        Route::post('logout', 'LogoutController')->middleware('auth:api');
    });

    Route::apiResource('posts', 'PostController')->except(['index', 'show'])
        ->middleware('auth:api');
    Route::apiResource('posts', 'PostController')->only(['index', 'show']);

    Route::apiResource('posts.comments', 'PostCommentController')->except(['index', 'show'])
        ->middleware('auth:api');
    Route::apiResource('posts.comments', 'PostCommentController')->only(['index', 'show']);
});

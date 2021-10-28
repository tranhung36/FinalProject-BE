<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Post\PostController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Post\PostController;
use App\Http\Controllers\Api\Topic\TopicController;
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

/**
 * Login & Register
 */
Route::post('login/', [LoginController::class, 'login']);
Route::post('register/', [RegisterController::class, 'register']);

/**
 * Auth
 */
Route::middleware(['auth:api'])->group(function () {
    Route::post('logout/', [LoginController::class, 'logout']);
    Route::resource('posts',PostController::class)->only(['store','update','destroy','show']);
    Route::resource('topics',TopicController::class)->only(['store','update','destroy','show']);

});

/**
 * Reset Password
 */
Route::post('forgot-password', [ResetPasswordController::class, 'forgotPassword']);
Route::put('reset-password', [ResetPasswordController::class, 'reset']);

<<<<<<< HEAD
//public route
Route::resource('posts',PostController::class)->only(['index']);
Route::resource('topics', TopicController::class)->only(['index']);

=======
/**
 * Post detail & create
 */
Route::get('posts/{slug}', [PostController::class, 'show']);
Route::middleware(['auth:api'])->group(function () {
    Route::post('create-post/', [PostController::class, 'store']);
});
>>>>>>> sprint1-post-detail

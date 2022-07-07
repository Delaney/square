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

Route::post('/register', 'AuthController@register')->name('register');
Route::post('/login', 'AuthController@login')->name('login');

Route::get('/posts', 'PostController@index')->name('posts');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/posts/{id}', 'PostController@show')->name('posts.show');
    Route::post('/post', 'PostController@create')->name('posts.create');

    Route::get('/user', 'UserController@index');
    Route::get('/user/posts', 'UserController@posts');
});

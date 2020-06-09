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


Route::group(['prefix' => 'auth'], function ($router) {

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');

});

// Route::get('/auth/user/', 'AuthController@me');

Route::get('/posts', 'PostController@index')->name('all.posts');
Route::get('/comments/store', 'CommentController@store')->name('comments.store');

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    Route::get('/auth/user/', 'AuthController@me');
    
    // Route::get('/posts', 'PostController@index')->name('posts.home');
    Route::post('/create/post', 'PostController@new')->name('create.post');
    Route::post('/delete/post/{id}', 'PostController@deletePost')->name('delete.post');
});
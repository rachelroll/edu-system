<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


// posts
Route::get('posts', 'PostController@index')->name('posts.index');
Route::get('posts/create', 'PostController@create')->name('posts.create');
Route::get('posts/{id}', 'PostController@show')->name('posts.show');

Route::post('posts/store', 'PostController@store')->name('posts.store');

// comments
Route::post('/comments/store', 'CommentController@store')->name('comments.store');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


# 用户点击登录按钮时请求的地址
Route::get('/auth/oauth', 'Auth\AuthController@oauth')->name('wechat.login');

# 微信接口回调地址
Route::get('/auth/callback', 'Auth\AuthController@callback')->name('wechat.callback');
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

use Illuminate\Support\Facades\Auth;

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


# �û������¼��ťʱ����ĵ�ַ
//Route::get('/auth/oauth', 'Auth\AuthController@oauth')->name('wechat.login');

# ΢�Žӿڻص���ַ
Route::get('/auth/callback', 'Auth\AuthController@callback')->name('wechat.callback');


Route::get('/SearchQuery', 'SearchController@search');

Route::get('/auth/oauth', function() {
    Auth::loginUsingId(1);
    //$user = Auth::user();
    return redirect('posts');
})->name('wechat.login');

// �ҵ�����
Route::get('/users/posts', 'UserController@post')->name('web.users.posts');
// �༭����
Route::get('/users/edit', 'UserController@edit')->name('web.users.edit');
// �ϴ�ͷ��
Route::get('/users/edit_avatar', 'UserController@editAvatar')->name('web.users.edit_avatar');
// ��������
Route::post('/users/store', 'UserController@store')->name('web.users.store');
//����ͷ��
Route::post('/users/store_avatar', 'UserController@storeAvatar')->name('web.users.store_avatar');

// ��˿��ע
Route::post('/fans/store', 'FansController@store')->name('web.fans.store');
Route::post('/fans/cancel', 'FansController@cancel')->name('web.fans.cancel');

// ֪ͨ
// �༭˽��
Route::get('/message/to/{id}', 'MessageController@create')->name('web.message.to');
//����(����)˽��
Route::post('/message/store', 'MessageController@store')->name('web.message.store');
// ����˽��
Route::get('/messages', 'MessageController@index')->name('web.messages');

// ����
Route::post('/like', 'LikeController@like');
Route::get('/likes', 'LikeController@index')->name('web.likes');

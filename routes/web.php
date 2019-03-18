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


// 关于 posts
Route::get('posts', 'PostController@index')->name('posts.index');
Route::get('posts/create', 'PostController@create')->name('posts.create');
Route::get('posts/{id}', 'PostController@show')->name('posts.show');
Route::post('posts/store', 'PostController@store')->name('posts.store');
Route::get('posts/user_colleciton/{id}', 'PostController@collect')->name('web.posts.user_collection');


// comments
Route::post('/comments/store', 'CommentController@store')->name('comments.store');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


# 用户点击登录按钮时请求的地址
//Route::get('/auth/oauth', 'Auth\AuthController@oauth')->name('wechat.login');

// 搜索
Route::get('/SearchQuery', 'SearchController@search');

// 我的文章
Route::get('/users/posts', 'UserController@post')->name('web.users.posts');
// 编辑资料
Route::get('/users/edit', 'UserController@edit')->name('web.users.edit');
// 上传头像
Route::get('/users/edit_avatar', 'UserController@editAvatar')->name('web.users.edit_avatar');
// 保存资料
Route::post('/users/store', 'UserController@store')->name('web.users.store');
//保存头像
Route::post('/users/store_avatar', 'UserController@storeAvatar')->name('web.users.store_avatar');

// 粉丝关注
Route::post('/fans/store', 'FansController@store')->name('web.fans.store');
Route::post('/fans/cancel', 'FansController@cancel')->name('web.fans.cancel');

// 通知
// 编辑私信
Route::get('/message/to/{id}', 'MessageController@create')->name('web.message.to');
//发送(保存)私信
Route::post('/message/store', 'MessageController@store')->name('web.message.store');
// 所有私信
Route::get('/messages', 'MessageController@index')->name('web.messages');

// 点赞
Route::post('/like', 'LikeController@like');
Route::get('/likes', 'LikeController@index')->name('web.likes');


// 给微信服务器访问的路由
Route::any('/wechat', 'WeChatController@serve');

// 通过中间件获取用户资料
Route::group(['middleware' => ['web', 'wechat.oauth:snsapi_userinfo']], function () {
    Route::get('/user', function () {
        $user = session('wechat.oauth_user.default'); // 拿到授权用户资料

        dd($user);
    });
});

Route::get('/auth/oauth', function() {
    Auth::loginUsingId(1);
    //$user = Auth::user();
    return redirect('posts');
})->name('wechat.login');

# 微信接口回调地址
Route::get('/auth/callback', 'Auth\AuthController@callback')->name('wechat.callback');


// 请求微信统一下单接口
Route::get('/payment/place_order', 'PaymentController@place_order')->name('web.payment.place_order');
// 接收微信支付状态的通知
Route::post('/payment/notify', 'paymentController@notify')->name('web.payment.notify');

// 请求微信接口, 查看订单支付状态
Route::get('/payment/paid', 'PaymentController@paid')->name('web.payment.paid');









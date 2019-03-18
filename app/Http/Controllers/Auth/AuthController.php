<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    # 用户点击微信登录按钮后，调用此方法请求微信接口
    public function oauth(Request $request)
    {
        return Socialite::with('weixin')->redirect('web.posts.show');
    }

    # 微信的回调地址
    public function callback(Request $request)
    {
        $oauthUser = Socialite::with('weixin')->stateless()->user();

        // 在这里可以获取到用户在微信的资料
        dd($oauthUser);

        // 接下来处理相关的业务逻辑



    }
}

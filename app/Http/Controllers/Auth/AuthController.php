<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    # �û����΢�ŵ�¼��ť�󣬵��ô˷�������΢�Žӿ�
    public function oauth(Request $request)
    {
        return Socialite::with('weixin')->redirect('web.posts.show');
    }

    # ΢�ŵĻص���ַ
    public function callback(Request $request)
    {
        $oauthUser = Socialite::with('weixin')->stateless()->user();

        // ��������Ի�ȡ���û���΢�ŵ�����
        dd($oauthUser);

        // ������������ص�ҵ���߼�



    }
}

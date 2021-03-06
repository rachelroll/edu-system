<?php

namespace App\Http\Controllers;

use App\Order;
use App\User;
use EasyWeChat\Factory;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class AuthController extends Controller
{

    public function oauthCallback()
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
        $code = request()->get('code');
        $path = request()->get('path', '/');
        if (!$code) {
            return abort(403);
        }
        $param = [
            'appid'        => config('wechat.official_account.default.app_id'),
            'secret'       => config('wechat.official_account.default.secret'),
            'code'         => $code,
            'grant_type'   => 'authorization_code',
            'redirect'     => config('app.url'),
            'redirect_uri' => config('wechat.official_account.default.oauth.callback'),
        ];

        $request_url = $url . '?' . http_build_query($param);

        $body = $this->requestGet($request_url);
        if (!isset($body->errcode)) {
            $user_info = $this->getUserInfo($body->access_token, $body->openid);
            $user = User::where('openid', $user_info->openid)->first();
            if (!$user) {
                $user = User::create([
                    'openid'      => $user_info->openid,
                    'nick_name'   => $user_info->nickname,
                    'wechat_name' => $user_info->nickname,
                    'sex'         => $user_info->sex,
                    'login_time'  => now(),
                    'login_ip'    => inet_pton(request()->ip()),
                    'created_ip'  => inet_pton(request()->ip()),
                    'city'        => $user_info->city,
                    'province'    => $user_info->province,
                    'country'     => $user_info->country,
                    'headimgurl'  => $user_info->headimgurl,
                ]);
            } else {
                $user->update([
                    'login_ip'   => inet_pton(request()->ip()),
                    'login_time' => now(),
                ]);
            }
            Auth::login($user, TRUE);

            return redirect()->to($path);
        }
    }

    /**
     * get wechat's userinfo
     *
     * @param $access_token
     * @param $openid
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    public function getUserInfo($access_token, $openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $request_url = $url . '?' . http_build_query([
                'access_token' => $access_token,
                'openid'       => $openid,
            ]);

        return $this->requestGet($request_url);
    }

    /**
     * @param string $request_url
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws GuzzleException
     */
    private function requestGet(string $request_url)
    {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $request_url);
        $body = json_decode($response->getBody());

        return $body;
    }

    public function checkTicket()
    {
        $ticket = request('ticket', '');

        if (!$ticket) {
            abort(403);
        }

        $user_id = Redis::get($ticket);
        $order = Order::where('user_id', $user_id)->latest()->first();
        if (!$user_id) {
            abort(403);
        }

        Auth::loginUsingId($user_id);
        Redis::del($ticket);

        $redirect_url = route('web.posts.show', [
            'id' => $order->post_id,
        ]);
        return redirect($redirect_url);

    }
}

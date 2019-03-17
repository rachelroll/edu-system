<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use EasyWeChat\Payment\Order;

class WeChatController extends Controller
{
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        Log::info('request arrived.');

        $app = app('wechat.official_account');
        $app->server->push(function($message){
            return "欢迎关注 overtrue！";
        });


        $app = $this->payment();

        $response = $app->handlePaidNotify(function($message, $fail){

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                echo 'success';
                Log::info('成功啦');
            } else {
                Log::error('失败啦');
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        $response->send(); // return $response;

        return $app->server->serve();
    }



}

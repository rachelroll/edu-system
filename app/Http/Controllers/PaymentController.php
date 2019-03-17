<?php

namespace App\Http\Controllers;

use App\Order;
use App\PayLog;
use App\Post;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    // 请求统一下单接口的公用配置
    private function payment()
    {
        $config = [
            // 必要配置
            'app_id' => config('wechat.payment.default.app_id'),
            'mch_id' => config('wechat.payment.default.mch_id'),
            'key'    => config('wechat.payment.default.key'),   // API 密钥
            'notify_url' => config('wechat.payment.default.notify_url'),   // 通知地址
        ];

        $app = Factory::payment($config);

        return $app;
    }

    // 向微信请求创建与支付订单
    public function place_order($id)
    {
        $order_sn = date('ymd').substr(time(),-5).substr(microtime(),2,5);
        $post = Post::where('id', $id)->first();
        $total_fee = $post->price;

        PayLog::create([
            'appid' => config('wechat.payment.default.app_id'),
            'mch_id' => config('wechat.payment.default.mch_id'),
            'out_trade_no' => $order_sn,
            'total_fee' => $total_fee
        ]);

        $app = $this->payment();

        $total_fee = env('APP_DEBUG') ? 1 : $total_fee;

        $result = $app->order->unify([
            'trade_type'       => 'NATIVE',
            'body'             => '投资平台-订单支付',
            'out_trade_no'     => $order_sn,
            'total_fee'        => $total_fee,
            'spbill_create_ip' => request()->ip(), // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
        ]);

        if ($result['result_code'] == 'SUCCESS') {
            $code_url = $result['code_url'];

            return view('web.order.create', compact('code_url'));
        }
    }

    // 接收微信支付状态的通知
    public function notify()
    {
        $app = $this->payment();

        $response = $app->handlePaidNotify(function($message, $fail){
            // 查看支付日志
            $payLog = PayLog::where('out_trade_no', $message['out_trade_no'])->first();
            // 查看订单
            $order = Order::where('out_trade_no', $message['out_trade_no'])->first();

            if (!$payLog || $payLog->paid_at || $order->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////


            // return_code 表示通信状态，不代表支付状态
            if ($message['return_code'] === 'SUCCESS') {
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    // 更新支付时间为当前时间
                    $payLog->paid_at = time();

                    //创建订单
                    Order::create([
                        'order_sn' => $message['out_trade_no'],
                        'total_fee' => $message['total_fee'],
                        'pay_log_id' => $payLog->id,
                        'status' => 1,
                        'user_id' => request()->user()->id,
                        'paid_at' => $payLog->paid_at,
                        'post_id' =>
                    ]);
                }
                // 不论支付成功还是失败, 都更新 PayLog(这里的所有字段名都与微信返回的字段一致即可)
                PayLog::where('out_trade_no', $message['out_trade_no'])->update([
                    'appid' => $message['appid'],
                    'bank_type' => $message['bank_type'],
                    'total_fee' => $message['total_fee'],
                    'trade_type' => $message['trade_type'],
                    'is_subscribe' => $message['is_subscribe'],
                    'mch_id' => $message['mch_id'],
                    'nonce_str' => $message['nonce_str'],
                    'openid' => $message['openid'],
                    'sign' => $message['sign'],
                    'cash_fee' => $message['cash_fee'],
                    'fee_type' => $message['fee_type'],
                    'transaction_id' => $message['transaction_id'],
                    'time_end' => $payLog->paid_at,
                    'result_code' => $message['result_code'],
                    'return_code' => $message['return_code'],
                    'err_code' => $message['err_code'],
                    'err_code_des' => $message['err_code_des'],
                    'device_info' => $message['device_info'],
                    'attach' => $message['attach']
                ]);
            } else {
                Log::error($fail);
                return $fail('通信失败，请稍后再通知我');
            }

            return true; // 返回处理完成
        });

        return $response;
    }

}

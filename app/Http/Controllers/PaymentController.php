<?php

namespace App\Http\Controllers;

use App\Order;
use App\PayLog;
use App\Post;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{

    // 请求统一下单接口的公用配置
    private function payment()
    {
        $config = [
            // 必要配置
            'app_id'     => config('wechat.payment.default.app_id'),
            'mch_id'     => config('wechat.payment.default.mch_id'),
            'key'        => config('wechat.payment.default.key'),   // API 密钥
            'notify_url' => config('wechat.payment.default.notify_url'),   // 通知地址
        ];

        $app = Factory::payment($config);

        return $app;
    }

    // 向微信请求创建与支付订单
    public function place_order(Request $request)
    {
        $id = $request->get('id');
        $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        $post_price = optional(Post::where('id', $id)->first())->pirce;
        PayLog::create([
            'appid'        => config('wechat.payment.default.app_id'),
            'mch_id'       => config('wechat.payment.default.mch_id'),
            'out_trade_no' => $order_sn,
            'post_id'      => $id,
        ]);

        $app = $this->payment();

        $total_fee = env('APP_DEBUG') ? 1 : $post_price;

        $result = $app->order->unify([
            'trade_type'       => 'NATIVE',
            'body'             => '教科文-订单支付',
            'out_trade_no'     => $order_sn,
            'total_fee'        => $total_fee,
            'spbill_create_ip' => request()->ip(), // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
        ]);
        if ($result['result_code'] == 'SUCCESS') {
            $code_url = $result['code_url'];

            return [
                'code'     => 200,
                'order_sn' => $order_sn,
                'html' => QrCode::size(200)->generate($code_url),
            ];
        }
    }

    // 接收微信支付状态的通知
    public function notify()
    {
        $app = $this->payment();

        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 查看订单
            $order = Order::where('order_sn', $message['out_trade_no'])->first();
            if ($order) {
                return TRUE; // 如果已经生成订单, 表示已经处理完了, 告诉微信不用再通知了
            }
            // 查看支付日志
            $payLog = PayLog::where('out_trade_no', $message['out_trade_no'])->first();

            if (!$payLog || $payLog->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                return TRUE; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // return_code 表示通信状态，不代表支付状态
            if ($message['return_code'] === 'SUCCESS') {
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {
                    // 更新支付时间为当前时间
                    $payLog->paid_at = now();
                    $post_id = $payLog->post_id;
                    $post_title = $payLog->post->title;
                    $post_price = $payLog->post->price;
                    $post_original_price = $payLog->post->original_price;
                    $post_cover = $payLog->post->post_cover;
                    $post_description = $payLog->post->description;
                    $user_id = $payLog->post->user_id;

                    //创建订单
                    Order::create([
                        'order_sn'            => $message['out_trade_no'],
                        'total_fee'           => $message['total_fee'],
                        'pay_log_id'          => $payLog->id,
                        'status'              => 1,
                        'user_id'             => $user_id,
                        'paid_at'             => $payLog->paid_at,
                        'post_id'             => $post_id,
                        'post_title'          => $post_title,
                        'post_price'          => $post_price,
                        'post_original_price' => $post_original_price,
                        'post_cover'          => $post_cover,
                        'post_description'    => $post_description,
                    ]);

                    // 更新支付日志
                    PayLog::where('out_trade_no', $message['out_trade_no'])->update([
                        'appid'          => $message['appid'],
                        'bank_type'      => $message['bank_type'],
                        'total_fee'      => $message['total_fee'],
                        'trade_type'     => $message['trade_type'],
                        'is_subscribe'   => $message['is_subscribe'],
                        'mch_id'         => $message['mch_id'],
                        'nonce_str'      => $message['nonce_str'],
                        'openid'         => $message['openid'],
                        'sign'           => $message['sign'],
                        'cash_fee'       => $message['cash_fee'],
                        'fee_type'       => $message['fee_type'],
                        'transaction_id' => $message['transaction_id'],
                        'time_end'       => $payLog->paid_at,
                        'result_code'    => $message['result_code'],
                        'return_code'    => $message['return_code'],
                    ]);
                }
            } else {
                Log::error($fail);
                PayLog::where('out_trade_no', $message['out_trade_no'])->update([
                    'appid'          => $message['appid'],
                    'bank_type'      => $message['bank_type'],
                    'total_fee'      => $message['total_fee'],
                    'trade_type'     => $message['trade_type'],
                    'is_subscribe'   => $message['is_subscribe'],
                    'mch_id'         => $message['mch_id'],
                    'nonce_str'      => $message['nonce_str'],
                    'openid'         => $message['openid'],
                    'sign'           => $message['sign'],
                    'cash_fee'       => $message['cash_fee'],
                    'fee_type'       => $message['fee_type'],
                    'transaction_id' => $message['transaction_id'],
                    'time_end'       => $payLog->paid_at,
                    'result_code'    => $message['result_code'],
                    'return_code'    => $message['return_code'],
                    'err_code'       => $message['err_code'],
                    'err_code_des'   => $message['err_code_des'],
                ]);

                return $fail('通信失败，请稍后再通知我');
            }

            return TRUE; // 返回处理完成
        });

        return $response;
    }

    public function paid(Request $request)
    {
        $out_trade_no = $request->get('out_trade_no');

        $app = $this->payment();
        $result = $app->order->queryByOutTradeNumber($out_trade_no);
        if ($result['trade_state'] === 'SUCCESS') {
            return [
                'code' => 200,
                'msg'  => '支付成功',
            ];
        } else {
            return [
                'code' => 202,
                'msg'  => 'not paid',
            ];
        }
    }
}

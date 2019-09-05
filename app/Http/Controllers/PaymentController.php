<?php

namespace App\Http\Controllers;

use App\Order;
use App\PayLog;
use App\Post;
use App\User;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Laravolt\Avatar\Avatar;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentController extends Controller
{

    // 请求统一下单接口的公用配置
    private function getApp()
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
    public function placeOrder(Request $request)
    {
        $id = $request->get('id');
        $order_sn = date('ymd') . substr(time(), -5) . substr(microtime(), 2, 5);
        $post = Post::where('id', $id)->first();
        if (!$post) {
            return [
                'code'   => -1,
                'status' => 'FAILD',
                'msg'    => '参数错误',
            ];
        }
        $post_price = $post->pirce;
        PayLog::create([
            'appid'        => config('wechat.payment.default.app_id'),
            'mch_id'       => config('wechat.payment.default.mch_id'),
            'out_trade_no' => $order_sn,
            'post_id'      => $id,
        ]);

        $total_fee = env('APP_DEBUG') ? 1 : $post_price;
        $app = $this->getApp();
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
                'code'   => 200,
                'status' => 'success',
                'data'   => [
                    'order_sn' => $order_sn,
                    'html'     => QrCode::size(200)->margin(0)->generate($code_url),
                ],
            ];
        }
    }

    // 接收微信支付状态的通知
    public function notify()
    {
        $app = $this->getApp();

        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 查看订单
            $order = Order::where('order_sn', $message['out_trade_no'])->first();
            if ($order) {
                return TRUE; // 如果已经生成订单, 表示已经处理完了, 告诉微信不用再通知了
            }
            // 查看支付日志
            $payLog = PayLog::with('post')->where('out_trade_no', $message['out_trade_no'])->first();

            if (!$payLog || $payLog->paid_at) { // 如果订单不存在 或者 订单已经支付过了
                return TRUE; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // return_code 表示通信状态，不代表支付状态
            if ($message['return_code'] === 'SUCCESS') {
                $time_end = date('Y-m-d H:i:s', strtotime($message['time_end']));
                // 用户是否支付成功
                if ($message['result_code'] === 'SUCCESS') {

                    $user = $this->getUser($message['openid']);

                    // 更新支付时间为当前时间

                    $post = $payLog->post;

                    //创建订单
                    $order = Order::firstOrcreate([
                        'order_sn' => $message['out_trade_no'],
                    ], [
                        'total_fee'           => $message['total_fee'],
                        'pay_log_id'          => $payLog->id,
                        'status'              => 1,
                        'user_id'             => $user->id,
                        'paid_at'             => $time_end,
                        'post_id'             => $payLog->post_id,
                        'post_title'          => $post->title,
                        'post_price'          => $post->price,
                        'post_original_price' => $post->original_price,
                        'post_cover'          => $post->post_cover,
                        'post_description'    => $post->description,
                    ]);

                    // 更新支付日志
                    $payLog->update([
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
                        'time_end'       => $time_end,
                        'order_id'       => $order->id,
                        'result_code'    => $message['result_code'],
                        'return_code'    => $message['return_code'],
                    ]);

                    return TRUE; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
                }

                Log::error($fail);
                $payLog->update([
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
                    'time_end'       => $time_end,
                    'result_code'    => $message['result_code'],
                    'return_code'    => $message['return_code'],
                    'err_code'       => $message['err_code'],
                    'err_code_des'   => $message['err_code_des'],
                ]);

                return $fail('通信失败，请稍后再通知我');
            }

            return $fail('通信失败，请稍后再通知我');
        });

        return $response;
    }

    public function status(Request $request)
    {
        $out_trade_no = $request->get('out_trade_no');

        $app = $this->getApp();
        $result = $app->order->queryByOutTradeNumber($out_trade_no);
        if ($result['result_code'] === 'SUCCESS' && $result['trade_state'] === 'SUCCESS') {
            $openid = $result['openid'];
            $user = $this->getUser($openid);
            $seconds = 40;
            //如果支付时间在40秒之内,允许生成ticket,用户带上ticket 然后跳转去登录
            if (time() - strtotime($result['time_end']) < $seconds) {

                $ticket = password_hash($user->id, CRYPT_BLOWFISH);
                $ticket = md5($ticket);

                Redis::set($ticket, $user->id, 'EX', $seconds);
            }

            //如果是本地环境之间 处理支付结果
            if (env('APP_DEBUG') && env('APP_ENV') == 'local') {
                // 更新支付时间为当前时间
                $payLog = PayLog::with('post')->where('out_trade_no', $out_trade_no)->first();
                $time_end = date('Y-m-d H:i:s', strtotime($result['time_end']));
                $post = $payLog->post;

                //创建订单
                $order = Order::firstOrcreate([
                    'order_sn' => $out_trade_no,
                ], [
                    'total_fee'           => $result['total_fee'],
                    'pay_log_id'          => $payLog->id,
                    'status'              => 1,
                    'user_id'             => $user->id,
                    'paid_at'             => $time_end,
                    'post_id'             => $payLog->post_id,
                    'post_title'          => $post->title,
                    'post_price'          => $post->price,
                    'post_original_price' => $post->original_price,
                    'post_cover'          => $post->post_cover,
                    'post_description'    => $post->description,
                ]);
                // 更新支付日志
                $payLog->update([
                    'appid'          => $result['appid'],
                    'bank_type'      => $result['bank_type'],
                    'total_fee'      => $result['total_fee'],
                    'trade_type'     => $result['trade_type'],
                    'is_subscribe'   => $result['is_subscribe'],
                    'mch_id'         => $result['mch_id'],
                    'nonce_str'      => $result['nonce_str'],
                    'openid'         => $result['openid'],
                    'sign'           => $result['sign'],
                    'cash_fee'       => $result['cash_fee'],
                    'fee_type'       => $result['fee_type'],
                    'transaction_id' => $result['transaction_id'],
                    'time_end'       => $time_end,
                    'order_id'       => $order->id,
                    'result_code'    => $result['result_code'],
                    'return_code'    => $result['return_code'],
                ]);
            }

            return [
                'code' => 200,
                'msg'  => '支付成功',
                'status'=>'SUCCESS',
                'data'=>[
                    'ticket'=> $ticket,
                ]
            ];
        }
        return [
            'code' => -1,
            'msg'  => 'not paid',
            'status'=>'FAIL',
        ];
    }

    /**
     * @param $openid
     *
     * @return User
     */
    private function getUser($openid)
    {
        $user = User::where('openid', $openid)->first();
        if (!$user) {

            $config = config('laravolt.avatar');
            $avatar = new Avatar($config);
            $avatar_name = time() . '.png';
            $avatar->create($openid)->save(storage_path('app/public/' . $avatar_name), 100);

            $user = User::create([
                'openid'     => $openid,
                'nick_name'  => 'u_' . strtolower(substr($openid, 22, 6)),
                'login_time' => now(),
                'avatar'     => $avatar_name,
            ]);
        } else {
            $user->update([
                'login_time' => now(),
            ]);
        }

        return $user;
    }
}

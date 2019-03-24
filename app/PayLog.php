<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PayLog
 *
 * @property-read \App\Order $order
 * @property-read \App\Post $post
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $appid 微信分配的公众账号ID
 * @property string $mch_id 微信支付分配的商户号
 * @property string $bank_type 付款银行
 * @property int $cash_fee 现金支付金额
 * @property string $fee_type 货币种类
 * @property string $is_subscribe 是否关注公众账号
 * @property string $nonce_str 随机字符串
 * @property string $openid 用户标识
 * @property string $out_trade_no 商户系统内部订单号
 * @property string $result_code 业务结果
 * @property string $return_code 通信标识
 * @property string $sign 签名
 * @property string $prepay_id 微信生成的预支付回话标识，用于后续接口调用中使用，该值有效期为2小时
 * @property string|null $time_end 支付完成时间
 * @property int $total_fee 订单金额
 * @property string $trade_type 交易类型
 * @property string $transaction_id 微信支付订单号
 * @property string $err_code 错误代码
 * @property string $err_code_des 错误代码描述
 * @property string $device_info 设备号
 * @property string|null $attach 商家数据包
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $post_id 文章id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereAppid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereAttach($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereBankType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereCashFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereDeviceInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereErrCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereErrCodeDes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereFeeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereIsSubscribe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereMchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereNonceStr($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereOutTradeNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog wherePrepayId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereResultCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereReturnCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereTotalFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereTradeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PayLog whereUpdatedAt($value)
 */
class PayLog extends Model
{

    protected $fillable = ['appid', 'mch_id', 'out_trade_no', 'post_id'];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

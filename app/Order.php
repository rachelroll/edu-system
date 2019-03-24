<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Order
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PayLog[] $payLog
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 用户ID
 * @property float $total_fee 总价
 * @property string $order_sn 订单编号
 * @property string $post_title 文章标题
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $post_id
 * @property string|null $post_description 文章简介
 * @property int|null $post_price 付款价格
 * @property int|null $post_original_price 原价
 * @property string|null $post_cover 封面图
 * @property string|null $paid_at 支付时间
 * @property int $pay_log_id 支付日志 id
 * @property int $status 支付状态 0:未支付  1: 已支付
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereOrderSn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePayLogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePostCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePostDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePostOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order wherePostTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereTotalFee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Order whereUserId($value)
 */
class Order extends Model
{

    protected $fillable = [
        'post_id',
        'user_id',
        'order_sn',
        'total_fee',
        'post_title',
        'post_price',
        'post_original_price',
        'post_cover',
        'post_description',
        'paid_at',
        'pay_log_id',
        'status'
    ];

    public function payLog()
    {
        return $this->hasMany(PayLog::class);
    }
}

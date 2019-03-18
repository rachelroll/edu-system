<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

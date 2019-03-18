<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

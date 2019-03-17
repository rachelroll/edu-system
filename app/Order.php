<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $fillable = [
        'post_id', 'user_id', 'order_sn', 'total_fee', 'title'
    ];
}

<?php

namespace App\Http\Controllers;

use App\Order;
use App\Post;
use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderController extends Controller
{

    // 生成订单
    //public function store()
    //{
    //    $post_id = $request->input('post_id', '');
    //    $fee = $request->input('price', '');
    //    $title = $request->input('post_title', '');
    //    $user_id = request()->user()->id;
    //
    //    $res = Order::create([
    //        'post_id' => $post_id,
    //        'user_id' => $user_id,
    //        'order_sn' => $order_sn,
    //        'total_fee' => $fee,
    //        'title' => $title,
    //    ]);
    //}
}

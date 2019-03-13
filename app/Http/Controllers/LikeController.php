<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LikeController extends Controller
{

    //点赞
    public function like()
    {
        $user_id = request()->user()->id;
        $post_id = request('id');

        // post_set 保存所有被 like 的文章
        Redis::sadd('post_set', $post_id);

        $mysql_like = DB::table('user_like_post')->where('post_id', $post_id)->where('user_id', $user_id)->first();
        $redis_like = Redis::sadd($post_id, $user_id);

        if(empty($mysql_like) && $redis_like) {
            //post_{$post_id}_counter 对每个post维护一个计数器， 用来记录当前在redis中的点赞数
            Redis::incr('likes_count'.$post_id);

            return [
                'code' => 200,
                'msg'  => 'LIKE',
            ];
        }else{
            Redis::decr('likes_count'.$post_id);
            Redis::srem($post_id, $user_id);
            return [
                'code' => 202,
                'msg'  => 'UNLIKE',
            ];
        }
    }

    // 查看我所有的赞过/收藏的文章
    public function index()
    {
        $user_id = request()->user()->id;

    }
}

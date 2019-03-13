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
        // 获取当前登录用户的 id
        $user_id = request()->user()->id;
        // 获取被点赞的文章 id, 从 get 请求参数中获取
        $post_id = request('id');

        // post_set 用 redis 的 set 类型, 保存所有被 like 的文章
        Redis::sadd('post_set', $post_id);

        // 根据 post_id 和 user_id, 查询 user_like_post 表, 看当前登录用户是否有曾经赞过这篇文章的记录
        $mysql_like = DB::table('user_like_post')->where('post_id', $post_id)->where('user_id', $user_id)->first();
        /*
        根据 post_id 和 user_id, 查询 redis 里是否有当前登录用户是否有曾经赞过这篇文章的记录.
        利用 set 值是要求唯一的特点:
        如果当前用户曾经赞过这篇文章, 则添加不成功, sadd() 返回 0;
        如果没有赞过, 则会将当前用户 id 添加到这篇文章的 set 里, 并且返回 1.
        */
        $redis_like = Redis::sadd($post_id, $user_id);

        // 如果 mysql 中没有记录 且 redis 添加成功, 点赞成功
        if(empty($mysql_like) && $redis_like) {
            // 将这篇文章的点赞计数 加一
            Redis::incr('likes_count'.$post_id);

            //返回点赞成功
            return [
                'code' => 200,
                'msg'  => 'LIKE',
            ];
            // 反之, 不管是 mysql 中还是 redis 中有过点赞记录, 此次操作均被视为取消点赞
        }else{
            // 将这篇文章的点赞计数 减一
            Redis::decr('likes_count'.$post_id);
            // 从这篇文章的 set 中, 删除当前用户 ID
            Redis::srem($post_id, $user_id);

            // 返回为 取消点赞
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

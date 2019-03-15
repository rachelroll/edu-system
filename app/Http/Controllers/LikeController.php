<?php

namespace App\Http\Controllers;

use App\Post;
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
        // 获取当前登录用户的信息
        $user_id = request()->user()->id;
        $user_name = request()->user()->name;
        $user_avatar = request()->user()->avatar;

        // 获取被点赞的文章的信息
        $post_id = request('id');
        $title = request('post_title');
        $description = request('post_description');

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
        if (empty($mysql_like) && $redis_like) {
            // 将这篇文章的点赞计数 加一
            Redis::incr('likes_count' . $post_id);
            // 给点赞的用户的 ordered set 里增加文章 ID, 用时间戳做 score, 根据点赞的时间排序
            Redis::zadd('user' . $user_id, strtotime(now()), $post_id);
            // 用 hash 保存每一个赞的快照
            Redis::hmset('post_user_like_'.$post_id.'_'.$user_id,
                'user_id', $user_id,
                'user_name', $user_name,
                'user_avatar', $user_avatar,
                'post_id', $post_id,
                'post_title', $title,
                'post_description', $description,
                'ctime', now()
            );

            //返回点赞成功
            return [
                'code' => 200,
                'msg'  => 'LIKE',
            ];
            // 反之, 不管是 mysql 中还是 redis 中有过点赞记录, 此次操作均被视为取消点赞
        } else {
            // 将这篇文章的点赞计数减一
            Redis::decr('likes_count' . $post_id);
            // 从这篇文章的 set 中, 删除当前用户 ID
            Redis::srem($post_id, $user_id);
            // 从当前用户赞的文章集合中, 删除这篇文章
            Redis::zrem('user' . $user_id, $post_id);
            // 从 mysql 中删除这条点赞记录
            DB::table('user_like_post')->where('post_id', $post_id)->where('user_id', $user_id)->delete();

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
        // 从 mysql 中取出当前登录用户所有的点赞文章
        $post_mysql = DB::table('user_like_post')->where('user_id', $user_id)->orderBy('created_at')->get();

        // 从 redis 中取出当前用户点赞文章的 id
        $post_in_redis = Redis::zrange('user'.$user_id, 0, -1);
        if ($post_in_redis) {
            // 由于 sorted set 存储的原则是 score 值由小到大排序, 最新收藏的时间戳的值肯定是最大的, 会排在后面, 所以这里将上面取出来的数组倒序遍历
            foreach (array_reverse($post_in_redis) as $post_id) {
                // 根据文章 id 和用户 id 从点赞快照中取出点赞的相关信息
                $posts_redis[] = Redis::hgetall('post_user_like_'.$post_id.'_'.$user_id);
            }
            // 合并 mysql 和 redis 里的数据
            $posts = array_merge($posts_redis, json_decode($post_mysql, 1));
        }else{
            $posts = $post_mysql->toArray();
        }
        return view('web.likes.index', compact('posts'));
    }
}

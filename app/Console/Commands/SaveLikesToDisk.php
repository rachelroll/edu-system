<?php

namespace App\Console\Commands;

use App\Like;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SaveLikesToDisk extends Command
{
    // 设置定时任务时用
    protected $signature = 'likestodisk:save';

    // 无用, 所以我也没写
    protected $description = 'Command description';

    // 目前不知道啥用
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 求出 redis 中 共有多少篇文章被点赞了, 这里得到是一个整数值
        $liked_posts = Redis::scard('post_set');
        // 有多少篇文章被赞, 就循环多少次
        for ($i = 0; $i < $liked_posts; $i++) {
            // 从存放被赞的文章的 set 中 pop 出一篇文章, 即获得 post_id. spop() 方法的特点是随机返回一个值, 并从 set 中删除这个值
            $post_id = Redis::spop('post_set');
            // 根据上面取出的文章ID, 查看这篇文章的 set 里共有多少个用户点赞
            $users = Redis::scard($post_id);
            // 有多少用户, 就循环多少次
            for ($j = 0; $j < $users; $j++) {
                // 取出一个给这篇文章点赞的用户
                $user_id = Redis::spop($post_id);
                // 根据文章 ID 和用户 ID, 从保存点赞快照的 hash 里取出所有信息
                $key = 'post_user_like_'.$post_id.'_'.$user_id;

                $post_title = Redis::hget($key, 'post_title');
                $post_description = Redis::hget($key, 'post_description');
                $user_name = Redis::hget($key, 'user_name');
                $user_avatar = Redis::hget($key, 'user_avatar');
                $ctime = Redis::hget($key, 'ctime');

                // 把信息存入 user_like_post 表, 也就是保存点赞的具体细节
                DB::table('user_like_post')->insert([
                    'user_id' => $user_id,
                    'post_id' => $post_id,
                    'post_title' => $post_title,
                    'post_description' => $post_description,
                    'user_name' => $user_name,
                    'user_avatar' => $user_avatar,
                    'created_at' => $ctime
                ]);
            }

            // 根据文章 ID 从点赞计数的 set 里取出这篇文章共有多少个赞
            $count = Redis::get('likes_count' . $post_id);

            // 根据文章 ID 查看 mysql likes 表, 看原来是否有这篇文章的记录
            $res = DB::table('likes')->where('post_id', $post_id)->first();
            if ($res) {
                // 如果原来有这篇文章的记录, 看原来有多少个赞
                $old_count = $res->count;
                // 把原来的赞和新的赞加和后, 更新 mysql 数据库
                $count += $old_count;
                DB::table('likes')->where('post_id', $post_id)->update(['count' => $count]);
            }else{
                // 如果原来没有这篇文章的记录, 插入记录
                DB::table('likes')->updateOrInsert([
                    'post_id' => $post_id,
                    'count' => $count,
                ]);
            }
        }
        //清空缓存
        Redis::flushDB();
    }
}

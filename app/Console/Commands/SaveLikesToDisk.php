<?php

namespace App\Console\Commands;

use App\Like;
use App\Post;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
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
                // 这里获取到了文章ID 和用户ID, 就可以根据业务逻辑取出需要的信息
                $post = Post::select('title', 'description')->where('id', $post_id)->first();
                $user = User::select('name', 'avatar')->where('id', $user_id)->first();
                // 把信息存入 user_like_post 表, 也就是保存点赞的具体细节
                DB::table('user_like_post')->insert([
                    'user_id' => $user_id,
                    'post_id' => $post_id,
                    'post_title' => $post->title,
                    'post_description' => $post->description,
                    'user_name' => $user->name,
                    'user_avatar' => $user->avatar,
                ]);
            }

            // 根据文章ID 从点赞计数的 set 里取出这篇文章共有多少个赞
            $count = Redis::get('likes_count' . $post_id);
            // 存入 likes 表, 也就是对每篇文章的点赞数统计表
            Like::create([
                'post_id' => $post_id,
                'count' => $count,
            ]);
        }
        //清空缓存
        Redis::flushDB();
    }
}

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
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'likestodisk:save';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
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
        $liked_posts = Redis::scard('post_set');
        for ($i = 0; $i < $liked_posts; $i++) {
            $post_id = Redis::spop('post_set');
            $users = Redis::scard($post_id);
            for ($j = 0; $j < $users; $j++) {
                $user_id = Redis::spop($post_id);
                $post = Post::where('id', $post_id)->first();
                $user = User::where('id', $user_id)->first();
                DB::table('user_like_post')->insert([
                    'user_id' => $user_id,
                    'post_id' => $post_id,
                    'post_title' => $post->title,
                    'post_description' => $post->description,
                    'user_name' => $user->name,
                    'user_avatar' => $user->avatar,
                ]);
                $count = Redis::get('likes_count' . $post_id);
                Like::create([
                    'post_id' => $post_id,
                    'count' => $count,
                ]);
            }
        }
        //Çå¿Õ»º´æ
        Redis::flushDB();
    }
}

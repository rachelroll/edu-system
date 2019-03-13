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
    // ���ö�ʱ����ʱ��
    protected $signature = 'likestodisk:save';

    // ����, ������Ҳûд
    protected $description = 'Command description';

    // Ŀǰ��֪��ɶ��
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
        // ��� redis �� ���ж���ƪ���±�������, ����õ���һ������ֵ
        $liked_posts = Redis::scard('post_set');
        // �ж���ƪ���±���, ��ѭ�����ٴ�
        for ($i = 0; $i < $liked_posts; $i++) {
            // �Ӵ�ű��޵����µ� set �� pop ��һƪ����, ����� post_id. spop() �������ص����������һ��ֵ, ���� set ��ɾ�����ֵ
            $post_id = Redis::spop('post_set');
            // ��������ȡ��������ID, �鿴��ƪ���µ� set �ﹲ�ж��ٸ��û�����
            $users = Redis::scard($post_id);
            // �ж����û�, ��ѭ�����ٴ�
            for ($j = 0; $j < $users; $j++) {
                // ȡ��һ������ƪ���µ��޵��û�
                $user_id = Redis::spop($post_id);
                // �����ȡ��������ID ���û�ID, �Ϳ��Ը���ҵ���߼�ȡ����Ҫ����Ϣ
                $post = Post::select('title', 'description')->where('id', $post_id)->first();
                $user = User::select('name', 'avatar')->where('id', $user_id)->first();
                // ����Ϣ���� user_like_post ��, Ҳ���Ǳ�����޵ľ���ϸ��
                DB::table('user_like_post')->insert([
                    'user_id' => $user_id,
                    'post_id' => $post_id,
                    'post_title' => $post->title,
                    'post_description' => $post->description,
                    'user_name' => $user->name,
                    'user_avatar' => $user->avatar,
                ]);
            }

            // ��������ID �ӵ��޼����� set ��ȡ����ƪ���¹��ж��ٸ���
            $count = Redis::get('likes_count' . $post_id);
            // ���� likes ��, Ҳ���Ƕ�ÿƪ���µĵ�����ͳ�Ʊ�
            Like::create([
                'post_id' => $post_id,
                'count' => $count,
            ]);
        }
        //��ջ���
        Redis::flushDB();
    }
}

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
                // �������� ID ���û� ID, �ӱ�����޿��յ� hash ��ȡ��������Ϣ
                $key = 'post_user_like_'.$post_id.'_'.$user_id;

                $post_title = Redis::hget($key, 'post_title');
                $post_description = Redis::hget($key, 'post_description');
                $user_name = Redis::hget($key, 'user_name');
                $user_avatar = Redis::hget($key, 'user_avatar');
                $ctime = Redis::hget($key, 'ctime');

                // ����Ϣ���� user_like_post ��, Ҳ���Ǳ�����޵ľ���ϸ��
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

            // �������� ID �ӵ��޼����� set ��ȡ����ƪ���¹��ж��ٸ���
            $count = Redis::get('likes_count' . $post_id);

            // �������� ID �鿴 mysql likes ��, ��ԭ���Ƿ�����ƪ���µļ�¼
            $res = DB::table('likes')->where('post_id', $post_id)->first();
            if ($res) {
                // ���ԭ������ƪ���µļ�¼, ��ԭ���ж��ٸ���
                $old_count = $res->count;
                // ��ԭ�����޺��µ��޼Ӻͺ�, ���� mysql ���ݿ�
                $count += $old_count;
                DB::table('likes')->where('post_id', $post_id)->update(['count' => $count]);
            }else{
                // ���ԭ��û����ƪ���µļ�¼, �����¼
                DB::table('likes')->updateOrInsert([
                    'post_id' => $post_id,
                    'count' => $count,
                ]);
            }
        }
        //��ջ���
        Redis::flushDB();
    }
}

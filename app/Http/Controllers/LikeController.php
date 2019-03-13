<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LikeController extends Controller
{

    //����
    public function like()
    {
        $user_id = request()->user()->id;
        $post_id = request('id');

        // post_set �������б� like ������
        Redis::sadd('post_set', $post_id);

        $mysql_like = DB::table('user_like_post')->where('post_id', $post_id)->where('user_id', $user_id)->first();
        $redis_like = Redis::sadd($post_id, $user_id);

        if(empty($mysql_like) && $redis_like) {
            //post_{$post_id}_counter ��ÿ��postά��һ���������� ������¼��ǰ��redis�еĵ�����
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

    // �鿴�����е��޹�/�ղص�����
    public function index()
    {
        $user_id = request()->user()->id;

    }
}

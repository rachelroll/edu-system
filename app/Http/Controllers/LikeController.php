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
        // ��ȡ��ǰ��¼�û��� id
        $user_id = request()->user()->id;
        // ��ȡ�����޵����� id, �� get ��������л�ȡ
        $post_id = request('id');

        // post_set �� redis �� set ����, �������б� like ������
        Redis::sadd('post_set', $post_id);

        // ���� post_id �� user_id, ��ѯ user_like_post ��, ����ǰ��¼�û��Ƿ��������޹���ƪ���µļ�¼
        $mysql_like = DB::table('user_like_post')->where('post_id', $post_id)->where('user_id', $user_id)->first();
        /*
        ���� post_id �� user_id, ��ѯ redis ���Ƿ��е�ǰ��¼�û��Ƿ��������޹���ƪ���µļ�¼.
        ���� set ֵ��Ҫ��Ψһ���ص�:
        �����ǰ�û������޹���ƪ����, ����Ӳ��ɹ�, sadd() ���� 0;
        ���û���޹�, ��Ὣ��ǰ�û� id ��ӵ���ƪ���µ� set ��, ���ҷ��� 1.
        */
        $redis_like = Redis::sadd($post_id, $user_id);

        // ��� mysql ��û�м�¼ �� redis ��ӳɹ�, ���޳ɹ�
        if(empty($mysql_like) && $redis_like) {
            // ����ƪ���µĵ��޼��� ��һ
            Redis::incr('likes_count'.$post_id);

            //���ص��޳ɹ�
            return [
                'code' => 200,
                'msg'  => 'LIKE',
            ];
            // ��֮, ������ mysql �л��� redis ���й����޼�¼, �˴β���������Ϊȡ������
        }else{
            // ����ƪ���µĵ��޼��� ��һ
            Redis::decr('likes_count'.$post_id);
            // ����ƪ���µ� set ��, ɾ����ǰ�û� ID
            Redis::srem($post_id, $user_id);

            // ����Ϊ ȡ������
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

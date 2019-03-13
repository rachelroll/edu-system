<?php

namespace App\Http\Controllers;

use App\Fan;
use App\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FansController extends Controller
{

    public function store(Request $request)
    {
        // �������� id
        $author_id = $request->input('author_id', 0);
        // ��ǰ��¼�û� id
        $user_id = Auth::user()->id;

        // ���·�˿��
        Fan::create([
            'user_id' => $author_id,
            'fans_id' => $user_id,
        ]);

        // ���¹�ע��
        Follow::create([
                'user_id' => $user_id,
                'follow_id' => $author_id
            ]);

        return back();
    }

    public function cancel(Request $request)
    {
        // �������� id
        $author_id = $request->input('author_id', 0);
        // ��ǰ��¼�û� id
        $user_id = Auth::user()->id;

        // ɾ����˿��ļ�¼
        Fan::where('user_id', $author_id)->where('fans_id', $user_id)->delete();

        // ɾ����ע��ļ�¼
        Follow::where('user_id', $user_id)->where('follow_id', $author_id)->delete();

        return back();
    }
}

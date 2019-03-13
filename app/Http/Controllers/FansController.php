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
        // 文章作者 id
        $author_id = $request->input('author_id', 0);
        // 当前登录用户 id
        $user_id = Auth::user()->id;

        // 更新粉丝表
        Fan::create([
            'user_id' => $author_id,
            'fans_id' => $user_id,
        ]);

        // 更新关注表
        Follow::create([
                'user_id' => $user_id,
                'follow_id' => $author_id
            ]);

        return back();
    }

    public function cancel(Request $request)
    {
        // 文章作者 id
        $author_id = $request->input('author_id', 0);
        // 当前登录用户 id
        $user_id = Auth::user()->id;

        // 删除粉丝表的记录
        Fan::where('user_id', $author_id)->where('fans_id', $user_id)->delete();

        // 删除关注表的记录
        Follow::where('user_id', $user_id)->where('follow_id', $author_id)->delete();

        return back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $comment = $request->get('comments', '');
        $post_id = $request->get('post_id', '');
        $user_id = request()->user()->id;
        $user_name = request()->user()->name;
        $portrait = request()->user()->avatar;

        //Comment::create([
        //    'comments' => $comment,
        //    'user_id'  => $user_id,
        //    'post_id'  => $post_id,
        //    'name'     => $user_name,
        //    'portrait' => $portrait,
        //]);

        // 创建记录的同时检索出这条新记录的 id
        $id = DB::table('comments')->insertGetId([
            'comments' => $comment,
            'user_id'  => $user_id,
            'post_id'  => $post_id,
            'name'     => $user_name,
            'portrait' => $portrait,
        ]);

        $comment = Comment::findOrFail($id);

        // 分派事件
        event(new CommentSent($comment));

        return back()->with('success', '评论成功');
    }
}

<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Events\CommentSent;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{

    public function store(Request $request)
    {
        $comment = $request->get('comments', '');
        $post_id = $request->get('post_id', '');
        $post_title = $request->get('post_title', '');
        $commentator_id = $request->get('commentator_id', '');

        $user_id = request()->user()->id;
        $user_name = request()->user()->name;
        $portrait = request()->user()->avatar;

        $post = Post::where('id', $post_id)->first();

        // 判断评论是对文章作者的, 还是对其他评论人的
        if ($commentator_id) {
            $receiver_id = $commentator_id; // 对其他评论人的
        }else{
            $receiver_id = $post->user_id; // 对文章作者的
        }

        // 创建记录的同时检索出这条新记录的 id
        $id = DB::table('comments')->insertGetId([
            'comments' => $comment,
            'user_id'  => $user_id,
            'post_id'  => $post_id,
            'post_title' => $post_title,
            'commentator_name'     => $user_name,
            'commentator_portrait' => $portrait,
            'author_id' => $receiver_id,
            'author_name' => $post->author,
            "created_at" =>  \Carbon\Carbon::now(), # \Datetime()
            "updated_at" => \Carbon\Carbon::now(),  # \Datetime()
        ]);

        $comment = Comment::findOrFail($id);

        // 分派事件
        event(new CommentSent($comment));

        return back()->with('success', '评论成功');
    }
}

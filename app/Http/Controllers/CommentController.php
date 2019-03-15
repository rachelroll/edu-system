<?php

namespace App\Http\Controllers;

use App\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $comment = $request->get('comments', '');
        $post_id = $request->get('post_id', '');
        $user_id = request()->user()->id;
        $user_name = request()->user()->name;
        $portrait = request()->user()->avatar;

        Comment::create([
            'comments' => $comment,
            'user_id' => $user_id,
            'post_id' => $post_id,
            'name' => $user_name,
            'portrait' => $portrait,
        ]);

        return back()->with('success', '评论成功');
    }
}

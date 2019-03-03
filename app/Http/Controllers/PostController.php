<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 文章列表
    public function index()
    {
        $posts = Post::isChecked()->get();

        return view('web.posts.index', compact('posts'));
    }

    // 文章详情页
    public function show($id)
    {
        $post = Post::isChecked()->with('comments')->findOrFail($id);

        return view('web.posts.show', compact('post'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // 文章列表
    public function index()
    {
        $posts = Post::all();
        return view('posts/index', compact('posts'));
    }

    // 文章详情页
    public function show($id)
    {
        $post = Post::with('comments')->find($id);
        return view('posts/show', compact('post'));
    }
}

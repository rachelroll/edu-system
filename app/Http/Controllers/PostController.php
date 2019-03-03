<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // �����б�
    public function index()
    {
        $posts = Post::all();
        return view('posts/index', compact('posts'));
    }

    // ��������ҳ
    public function show($id)
    {
        $post = Post::with('comments')->find($id);
        return view('posts/show', compact('post'));
    }
}

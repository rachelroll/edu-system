<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // �����б�
    public function index()
    {
        $posts = Post::isChecked()->get();

        return view('web.posts.index', compact('posts'));
    }

    // ��������ҳ
    public function show($id)
    {
        $post = Post::isChecked()->with('comments')->findOrFail($id);

        return view('web.posts.show', compact('post'));
    }
}

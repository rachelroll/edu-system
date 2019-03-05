<?php

namespace App\Http\Controllers;

use App\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
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

    // д����ҳ��
    public function create()
    {
        return view('web.posts.create');
    }
}

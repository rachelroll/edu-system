<?php

namespace App\Http\Controllers;

use App\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // 写文章页面
    public function create()
    {
        return view('web.posts.create');
    }

    //保存文章
    public function store(Request $request)
    {
        $file = $request->file('cover', '');
        if (!$file) {
            return back()->with('error', '选一张门面图吧, 会展示在首页哦!');
        } else {
            $cover = $this->upload($file, 500);
        }
        $title = $request->input('title', '');
        $description = $request->input('description', '');
        $content = $request->input('content', '');
        $price = $request->input('price', 0);
        if ($price == 0) {
            $is_free = 1;
        } else {
            $is_free = 0;
        }
        $user_id = Auth::user()->id;
        $author = Auth::user()->name;

        $bool = Post::create([
            'user_id' => $user_id,
            'author' => $author,
            'title' => $title,
            'description' => $description,
            'content' => $content,
            'cover' => $cover,
            'price' => $price * 100,
            'is_free' => $is_free
        ]);

        if ($bool) {
            $post = Post::where('user_id', $user_id)->latest()->first();
            $id = $post->id;
            //$content = Markdown::convertToHtml($post->content);
            return redirect()->route('posts.show', [
                'id' => $id,
            ]);
        } else{
            return back()->with('error', '请稍后重试');
        }
    }
}

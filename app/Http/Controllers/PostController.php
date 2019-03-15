<?php

namespace App\Http\Controllers;

use App\Fan;
use App\Like;
use App\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class PostController extends Controller
{

    // 文章列表
    public function index()
    {
        $posts = Post::isChecked()->orderBy('updated_at', 'desc')->get();

        // 增加阅读量
        foreach ($posts as &$post) {
            $post->readed = Redis::get('post'.$post->id.'_readed');
        }

        return view('web.posts.index', compact('posts'));
    }

    // 文章详情页
    public function show($id)
    {
        $post = Post::isChecked()->with('comments')->findOrFail($id);
        $user_id = Auth::user()->id;
        $author_id = $post->user_id;

        // 是否关注
        $bool = Fan::where('user_id', $author_id)->where('fans_id', $user_id)->exists();

        // 获取文章的点赞数
        // 初始化为 0
        $like_counts = 0;
        // 获取 redis 中的点赞数
        $count_in_redis = Redis::get('likes_count'.$id);
        if (!is_null($count_in_redis)) {
            $like_counts += $count_in_redis;
        }

        // 获取 mysql 的点赞数
        $count_in_mysql = Like::where('post_id', $id)->first();
        if (!empty($count_in_mysql)) {
            // 加和
            $like_counts += $count_in_mysql->count;
        }

        // 阅读量
        $readed = Redis::incr('post'.$id.'_readed');

        return view('web.posts.show', compact('post', 'bool', 'like_counts', 'readed'));
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
            'user_id'     => $user_id,
            'author'      => $author,
            'title'       => $title,
            'description' => $description,
            'content'     => $content,
            'cover'       => $cover,
            'price'       => $price * 100,
            'is_free'     => $is_free,
        ]);

        if ($bool) {
            $post = Post::where('user_id', $user_id)->latest()->first();
            $id = $post->id;

            //$content = Markdown::convertToHtml($post->content);
            return redirect()->route('posts.show', [
                'id' => $id,
            ]);
        } else {
            return back()->with('error', '请稍后重试');
        }
    }
}

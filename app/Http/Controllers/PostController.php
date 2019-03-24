<?php

namespace App\Http\Controllers;

use App\Fan;
use App\Like;
use App\Post;
use App\User;
use App\Utils\Utils;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Parsedown;

class PostController extends Controller
{

    // 文章列表
    public function index()
    {
        $posts = Post::isChecked()->orderBy('updated_at', 'desc')->paginate(10);

        foreach ($posts as &$post) {
            // 阅读量
            $post->readed = Redis::get('post' . $post->id . '_readed');

            // 获取文章的点赞数
            // 初始化为 0
            $post->like = 0;
            // 获取 redis 中的点赞数
            $count_in_redis = Redis::get('likes_count' . $post->id);
            if (!is_null($count_in_redis)) {
                $post->like += $count_in_redis;
            }

            // 获取 mysql 的点赞数
            $count_in_mysql = Like::where('post_id', $post->id)->first();
            if (!empty($count_in_mysql)) {
                // 加和
                $post->like += $count_in_mysql->count;
            }
        }

        return view('web.posts.index', compact('posts'));
    }

    // 文章详情页
    public function show($id)
    {
        $post = Post::isChecked()->with('comments')->findOrFail($id);

        $user_id = Auth::user()->id;
        $author_id = $post->user_id;

        //判断用户是否可以可以看
        if (!$post->is_free || $post->price) {
            if (!Auth::check()) {
                $post->is_free = 0;
                $post->content = Utils::cutArticle($post->content);
            } else {
                $user = Auth::user()->load([
                    'orders' => function ($query) use ($id) {
                        $query->where('post_id', $id);
                    },
                ]);
                if (count($user->orders)) {
                    $post->is_free = 1;
                } else {
                    $post->content = Utils::cutArticle($post->content);
                }
            }
        }

        // 是否关注
        $bool = Fan::where('user_id', $author_id)->where('fans_id', $user_id)->exists();

        // 获取文章的点赞数
        // 初始化为 0
        $like_counts = 0;
        // 获取 redis 中的点赞数
        $count_in_redis = Redis::get('likes_count' . $id);
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
        $readed = Redis::incr('post' . $id . '_readed');

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
            return redirect()->route('web.posts.show', [
                'id' => $id,
            ]);
        } else {
            return back()->with('error', '请稍后重试');
        }
    }

    // 用户发布的所有文章
    public function collect($id)
    {
        $posts = Post::isChecked()->where('user_id', $id)->orderBy('updated_at', 'desc')->get();

        foreach ($posts as &$post) {
            // 阅读量
            $post->readed = Redis::get('post' . $post->id . '_readed');

            // 获取文章的点赞数
            // 初始化为 0
            $post->like = 0;
            // 获取 redis 中的点赞数
            $count_in_redis = Redis::get('likes_count' . $post->id);
            if (!is_null($count_in_redis)) {
                $post->like += $count_in_redis;
            }

            // 获取 mysql 的点赞数
            $count_in_mysql = Like::where('post_id', $post->id)->first();
            if (!empty($count_in_mysql)) {
                // 加和
                $post->like += $count_in_mysql->count;
            }
        }

        $user_id = Auth::user()->id;
        $author_id = $post->user_id;
        // 是否关注
        $bool = Fan::where('user_id', $author_id)->where('fans_id', $user_id)->exists();

        return view('web.posts.user_collection', compact('posts', 'bool'));
    }

    // 截取文章的一部分内容
    private function cutArticle($data, $str = "....")
    {
        $data = strip_tags($data);//去除html标记
        $pattern = "/&[a-zA-Z]+;/";//去除特殊符号
        $data = preg_replace($pattern, '', $data);

        // 设置只加载三分之一的内容
        $cut = strlen($data) * 3 / 10;

        $data = mb_strimwidth($data, 0, $cut, $str);

        return $data;
    }

}

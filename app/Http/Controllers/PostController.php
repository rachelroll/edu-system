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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
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

            if (!$post->cover) {
                $post->cover = '../img/cover.jpg';
            } else {
                $post->cover = config('edu.cdn_domain').'/'.$post->cover;
            }
        }

        return view('web.posts.index', compact('posts'));
    }

    // 文章详情页
    public function show($id)
    {
        $post = Post::isChecked()->with('comments')->findOrFail($id);

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
        if (Auth::user()) {
            $user_id = Auth::user()->id;
            // 是否关注

            $bool = Fan::where('user_id', $author_id)->where('fans_id', $user_id)->exists();
        } else {
            $bool = 0;
        }

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

        // 设置封面图, 如果用户不上传则默认使用图片
        $cover = '';
        // 接收 base64 编码的图片
        $data = $request->input('data', '');
        if ($data != "") {
            $cover = 'files/' . date('Y-m-d-h-i-s') . '-' . rand(0,100) . '.png'; // 生成图片名称
            $data = str_replace('data:image/png;base64,', '', $data); // 去掉多余字符
            Storage::disk('oss')->put($cover, base64_decode($data)); // 解码图片，并保存到阿里云
        }

        $title = $request->input('title', '');
        $description = $request->input('description', '');
        $content = $request->input('content', '');
        $price = $request->get('price', 0);
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
            'price'       => $price,
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

        return view('web.posts.user-collection', compact('posts', 'bool'));
    }

    // 修改文章
    public function edit($id)
    {
        $post = Post::find($id);

        return view('web.posts.edit', compact('post'));
    }

    // 删除文章
    public function delete($id)
    {
        Post::where('id', $id)->delete();
    }

    public function imagesUpload(Request $request)
    {
        $file = $request->file();

        $filename = $this->upload($file['file'], 500);

        return response()->json([
            'filename' => $filename
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $post = Post::where('user_id',$request->user()->id)->where('id',$id)->first();
        $post->update([
            'title'=>$request->title,
            'description'=>$request->description,
            'content'=>$request->content,
            'price'=>$request->price,
        ]);

        return back();

    }


}

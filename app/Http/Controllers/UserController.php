<?php

namespace App\Http\Controllers;

use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// 导入 Intervention Image Manager Class
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;


class UserController extends Controller
{
    // 我的文章
    public function post()
    {
        $user_id = Auth::user()->id;
        $posts = Post::where('user_id', $user_id)->where('is_checked', 1)->get();

        return view('web.users.posts', compact('posts'));
    }

    // 编辑资料
    public function edit()
    {
        return view('web.users.edit');
    }

    // 修改头像
    public function editAvatar()
    {
        return view('web.users.edit_avatar');
    }
    
    // 保存头像
    public function storeAvatar(Request $request)
    {
        $file = $request->file('avatar','');

        $avatar = $this->upload($file, 200);
        $user_id = Auth::user()->id;
        User::where('id', $user_id)
            ->update(['avatar' => $avatar]);

        return back()->with('success', '成功上传头像');
    }

    // 保存资料
    public function store(Request $request)
    {

    }
}

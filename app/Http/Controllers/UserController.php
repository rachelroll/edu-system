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
        $id = Auth::user()->id;
        $user_info = User::where('id', $id)->first();
        return view('web.users.edit', compact('user_info'));
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

        if ($file) {
            $avatar = $this->upload($file, 200);
            $user_id = Auth::user()->id;
            User::where('id', $user_id)
                ->update(['avatar' => $avatar]);

            return back()->with('success', '成功上传头像');
        }else{
            return back()->with('error', '请选择照片');
        }
    }

    // 保存个人信息
    public function store(Request $request)
    {
        $name = $request->input('user_name', '');
        $sex = $request->input('sex', '');
        $city = $request->input('city', '');
        $self_intro = $request->input('self_intro', '');
        $file = $request->file('payee_code', '');
        if (!empty($file)) {
            $payee_code = $this->upload($file, 300);
        }else {
            $payee_code = '';
        }

        $user_id = Auth::user()->id;
        User::where('id', $user_id)
            ->update([
                'name' => $name,
                'sex' => $sex,
                'city' => $city,
                'self_intro' => $self_intro,
                'payee_code' => $payee_code
                ]);

        return back()->with('success', '操作成功');
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // 所有私信
    public function index()
    {
        $id = Auth::user()->id;
        $messages_as_sender = Message::where('sender_id', $id);
        $messages = Message::where('receiver_id', $id)->union($messages_as_sender)->orderBy('created_at', 'desc')->get();
        return view('web.messages.index', compact('messages'));
    }

    // 编辑私信页面
    public function create($id)
    {
        $user = User::where('id', $id)->first();
        return view('web.messages.create', compact('user'));
    }

    // 发送(保存)私信
    public function store(Request $request)
    {
        $content = $request->input('content', '');
        $receiver_id = $request->input('receiver_id', 0);
        $receiver_name = $request->input('receiver_name', '');
        $receiver_avatar = $request->input('receiver_avatar', '');
        $sender_id = Auth::user()->id;
        $sender_name = Auth::user()->name;
        $sender_avatar = Auth::user()->avatar;

        Message::create([
            'receiver_id' => $receiver_id,
            'receiver_name' => $receiver_name,
            'receiver_avatar' => $receiver_avatar,
            'sender_id' => $sender_id,
            'sender_name' => $sender_name,
            'sender_avatar' => $sender_avatar,
            'content' => $content
        ]);

        return redirect()->route('web.messages')->with('success', '发送私信成功');
    }
}

<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // 通知列表
    public function index()
    {
        $user_id = Auth::user()->id;
        $notifications = Notification::where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        foreach ($notifications as &$notification) {
            $post = Post::where('id', $notification->post_id)->first();
            $notification->post_author_id = $post->user_id;
        }

        Notification::where('unreaded', 1)->update([
            'unreaded' => 0,
        ]);

        return view('web.notifications.index', compact('notifications'));
    }
}

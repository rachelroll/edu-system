<?php

namespace App\Http\Controllers;

use App\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    // 通知列表
    public function index()
    {
        $user_id = Auth::user()->id;
        $notifications = Notification::where('user_id', $user_id)->get();

        return view('web.notifications.index', compact('notifications'));
    }
}

<?php

namespace App\Listeners;

use App\Events\CommentSent;
use App\Notification;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendCommentNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CommentSent  $event
     * @return void
     */
    public function handle(CommentSent $event)
    {
        $post_id = $event->comment->post_id;
        $sender_id = $event->comment->user_id;
        $sender_name = $event->comment->commentator_name;
        $sender_portrait = $event->comment->commentator_portrait;
        $content = $event->comment->comments;
        $user_id = $event->comment->author_id;
        $receiver_name = $event->comment->author_name;
        $post_title = $event->comment->post_title;

        Notification::create([
            'post_id' => $post_id,
            'sender_id' => $sender_id,
            'sender_name' => $sender_name,
            'sender_portrait' => $sender_portrait,
            'content' => $content,
            'user_id' => $user_id,
            'receiver_name' => $receiver_name,
            'post_title' => $post_title
        ]);
    }
}

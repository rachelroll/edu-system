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
        $post_id = $event->post_id;
        $sender_id = $event->commentator__id;
        $sender_name = $event->commentator_name;
        $sender_portrait = $event->commentator_portrait;
        $content = $event->comment;

        Notification::create([
            'post_id' => $post_id,
            'sender_id' => $sender_id,
            'sender_name' => $sender_name,
            'sender_portrait' => $sender_portrait,
            'content' => $content
        ]);
    }
}

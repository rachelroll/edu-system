<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'post_id', 'sender_id', 'sender_name', 'sender_portrait', 'content', 'receiver_name', 'user_id', 'post_title'
    ];
}

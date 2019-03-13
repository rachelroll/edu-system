<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'receiver_id',
        'receiver_name',
        'receiver_avatar',
        'sender_id',
        'sender_name',
        'sender_avatar',
        'content'
    ];
}

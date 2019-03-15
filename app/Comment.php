<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'comments', 'user_id', 'post_id', 'portrait', 'name'
    ];
}

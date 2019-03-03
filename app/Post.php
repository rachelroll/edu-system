<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function scopeIsChecked($query)
    {
        return $query->where('is_checked',1);
    }
}

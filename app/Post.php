<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function scopeIsChecked($query)
    {
        return $query->where('is_checked',1);
    }
}

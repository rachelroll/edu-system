<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    protected $fillable = [
    'user_id', 'author', 'title', 'description', 'content', 'content', 'cover', 'price', 'is_free'
    ];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function scopeIsChecked($query)
    {
        return $query->where('is_checked',1);
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }
}

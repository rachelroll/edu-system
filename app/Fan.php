<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fan extends Model
{
    protected $fillable = [
        'user_id', 'fans_id'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Fan
 *
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 用户
 * @property int $fans_id 粉丝
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan whereFansId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fan whereUserId($value)
 */
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

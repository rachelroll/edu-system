<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Follow
 *
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $follow_id 被关注的用户的ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow whereFollowId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Follow whereUserId($value)
 */
class Follow extends Model
{
    protected $fillable = [
        'user_id', 'follow_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}

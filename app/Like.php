<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Like
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Like newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Like newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Like query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $post_id 文章ID
 * @property int $count 文章被赞数
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Like whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Like whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Like wherePostId($value)
 */
class Like extends Model
{

    protected $fillable = ['post_id', 'count'];
}

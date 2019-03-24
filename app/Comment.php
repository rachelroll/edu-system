<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Comment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $comments 评价内容
 * @property int $user_id 评价人 ID
 * @property int $post_id 文章 ID
 * @property string $commentator_name 评价人名字
 * @property string $commentator_portrait 评价人头像
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $author_id 被评价人 ID
 * @property string $author_name 被评价人名字
 * @property string $post_title 文章题目
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereAuthorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCommentatorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCommentatorPortrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment wherePostTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUserId($value)
 */
class Comment extends Model
{
    protected $fillable = [
        'comments', 'user_id', 'post_id', 'portrait', 'name', 'post_title'
    ];
}

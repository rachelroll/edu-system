<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * App\Post
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read mixed $comments_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post isChecked()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $author 作者
 * @property int $type 分类
 * @property string $title 文章标题
 * @property string $description 文章描述
 * @property string $content 文章内容
 * @property string $cover 列表封面图
 * @property string $pictures 插图
 * @property int $readed 阅读数
 * @property int $price 价格(分)
 * @property int $original_price 原价(分)
 * @property int $is_free 是否免费
 * @property int $is_checked 是否通过审核
 * @property int $user_id
 * @property int $series_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCover($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereIsChecked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereIsFree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereOriginalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePictures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereReaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUserId($value)
 */
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

    // 把单位 "分" 转成单位 "元"
    public function getPriceAttribute($value)
    {
        return $value / 100;
    }

    // 把单位 "元" 存成单位 "分"
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value * 100;
    }
}

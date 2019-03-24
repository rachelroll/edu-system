<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Notification
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $post_id 文章 ID
 * @property int $sender_id 通知人 ID
 * @property string $post_title 文章标题
 * @property string $content 通知内容
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $user_id 被通知人的 ID
 * @property string $sender_name 通知人 ID
 * @property string $sender_portrait 通知人头像
 * @property string $receiver_name 被通知人名字
 * @property string $receiver_portait 被通知人头像
 * @property int $unreaded 是否为新通知: 1: 新通知 | 0: 旧通知
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification wherePostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification wherePostTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereReceiverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereReceiverPortait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereSenderPortrait($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereUnreaded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Notification whereUserId($value)
 */
class Notification extends Model
{

    protected $fillable = [
        'post_id', 'sender_id', 'sender_name', 'sender_portrait', 'content', 'receiver_name', 'user_id', 'post_title'
    ];
}

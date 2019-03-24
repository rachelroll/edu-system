<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Message
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $content 内容
 * @property int $receiver_id 收信人_ID
 * @property int $sender_id 发送人_ID
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $receiver_name 收信人名字
 * @property string $sender_name 发信人名字
 * @property string $receiver_avatar 收信人头像
 * @property string $sender_avatar 发信人头像
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereReceiverAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereReceiverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereSenderAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Message whereUpdatedAt($value)
 */
class Message extends Model
{

    protected $fillable = [
        'receiver_id',
        'receiver_name',
        'receiver_avatar',
        'sender_id',
        'sender_name',
        'sender_avatar',
        'content'
    ];
}

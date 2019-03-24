<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Fan[] $fans
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Follow[] $follows
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Notification[] $notifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Post[] $posts
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @mixin \Eloquent
 * @property int $id
 * @property string|null $email 邮箱
 * @property string|null $mobile 手机号
 * @property string $password 密码
 * @property string $name 姓名
 * @property string $nick_name 昵称
 * @property string $wechat_name 微信昵称
 * @property string $avatar 头像
 * @property \Illuminate\Support\Carbon|null $email_verified_at 邮箱验证֤
 * @property string|null $login_time 登录时间
 * @property string $login_ip 登录IP
 * @property string $created_ip 创建IP
 * @property string $from_user_id 邀请人
 * @property int $register_type 注册来源:默认0:内部网站|第三方app/合作机构…
 * @property int $register_way 注册设备来源(默认0:web/ios/android)
 * @property string $uuid uuid
 * @property int $sex 性别, 0:男|1:女
 * @property string|null $sign 个性签名
 * @property string $openid openid
 * @property int $order_id 订单id
 * @property int $role 购买者, 0:买家|1:发布者
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $city 城市
 * @property string $payee_code 收款二维码
 * @property string $self_intro 个人简介
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereFromUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereMobile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereNickName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOpenid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePayeeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRegisterType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRegisterWay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSelfIntro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSign($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereWechatName($value)
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = true;

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function posts()
    {
        return $this->hasMany('App\Post');
    }

    public function fans()
    {
        return $this->hasMany('App\Fan');
    }

    public function follows()
    {
        return $this->hasMany('App\Follow');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}

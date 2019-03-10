<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->nullable()->unique()->comment('邮箱');
            $table->string('mobile')->nullable()->unique()->comment('手机号');
            $table->string('password')->default('')->comment('密码');
            $table->string('name')->default('')->comment('姓名');
            $table->string('nick_name')->default('')->comment('昵称');

            $table->string('wechat_name')->default('')->comment('微信昵称');
            $table->string('avatar')->default('')->comment('头像');
            $table->dateTime('email_verified_at')->nullable()->comment('邮箱验证');

            $table->dateTime('login_time')->nullable()->comment('登录时间');
            $table->string('login_ip')->default('')->comment('登录IP');
            $table->string('created_ip')->default('')->comment('创建IP');

            $table->string('from_user_id')->default('')->comment('邀请人');
            $table->tinyInteger('register_type')->default(0)->comment('注册来源:默认0:内部网站|第三方app/合作机构…');
            $table->tinyInteger('register_way')->default(0)->comment('注册设备来源(默认0:web/ios/android)');
            $table->string('uuid')->default('')->comment('uuid');

            $table->tinyInteger('sex')->default(0)->comment('性别, 0:男|1:女');
            $table->text('sign')->nullable()->comment('个性签名');

            $table->string('openid');
            $table->unsignedInteger('order_id')->comment('订单id');
            $table->tinyInteger('role')->default(0)->comment('购买者, 0:买家|1:发布者');

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

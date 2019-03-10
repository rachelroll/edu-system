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
            $table->string('email')->nullable()->unique()->comment('����');
            $table->string('mobile')->nullable()->unique()->comment('�ֻ���');
            $table->string('password')->default('')->comment('����');
            $table->string('name')->default('')->comment('����');
            $table->string('nick_name')->default('')->comment('�ǳ�');

            $table->string('wechat_name')->default('')->comment('΢���ǳ�');
            $table->string('avatar')->default('')->comment('ͷ��');
            $table->dateTime('email_verified_at')->nullable()->comment('������֤');

            $table->dateTime('login_time')->nullable()->comment('��¼ʱ��');
            $table->string('login_ip')->default('')->comment('��¼IP');
            $table->string('created_ip')->default('')->comment('����IP');

            $table->string('from_user_id')->default('')->comment('������');
            $table->tinyInteger('register_type')->default(0)->comment('ע����Դ:Ĭ��0:�ڲ���վ|������app/����������');
            $table->tinyInteger('register_way')->default(0)->comment('ע���豸��Դ(Ĭ��0:web/ios/android)');
            $table->string('uuid')->default('')->comment('uuid');

            $table->tinyInteger('sex')->default(0)->comment('�Ա�, 0:��|1:Ů');
            $table->text('sign')->nullable()->comment('����ǩ��');

            $table->string('openid');
            $table->unsignedInteger('order_id')->comment('����id');
            $table->tinyInteger('role')->default(0)->comment('������, 0:���|1:������');

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

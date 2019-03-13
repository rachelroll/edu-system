<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLikePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_like_post', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('post_id')->comment('���� ID');
            $table->unsignedInteger('user_id')->comment('������ ID');
            $table->string('post_title')->default('')->comment('���±���');
            $table->string('post_description')->default('')->comment('���¼��');
            $table->string('user_name')->default('')->comment('����������');
            $table->string('user_avatar')->default('')->comment('������ͷ��');

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
        Schema::dropIfExists('user_like_post');
    }
}

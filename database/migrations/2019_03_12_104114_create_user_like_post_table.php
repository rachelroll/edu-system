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

            $table->unsignedInteger('post_id')->comment('文章 ID');
            $table->unsignedInteger('user_id')->comment('点赞人 ID');
            $table->string('post_title')->default('')->comment('文章标题');
            $table->string('post_description')->default('')->comment('文章简介');
            $table->string('user_name')->default('')->comment('点赞人名字');
            $table->string('user_avatar')->default('')->comment('点赞人头像');

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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('comments')->default('')->comment('评论');
            $table->unsignedInteger('user_id')->default(0)->comment('用户 id');
            $table->unsignedInteger('post_id')->default(0)->comment('文章 id');
            $table->string('name')->default('')->comment('评论人');
            $table->string('portrait')->default('')->comment('评论人头像');

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
        Schema::dropIfExists('comments');
    }
}

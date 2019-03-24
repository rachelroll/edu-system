<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');

            $table->string('author')->default('')->comment('作者');
            $table->tinyInteger('type')->default(1)->comment('分类, 1:文章|2:视频');
            $table->string('title')->default('')->comment('文章标题');
            $table->string('description')->default('')->comment('文章描述');
            $table->text('content')->default('')->comment('文章内容');
            $table->string('cover')->default('')->comment('列表封面图');
            $table->string('pictures')->default('')->comment('插图');
            $table->unsignedInteger('readed')->default(0)->comment('阅读数');
            $table->unsignedInteger('price')->default(0)->comment('价格(分)');
            $table->unsignedInteger('original_price')->default(0)->comment('原价(分)');
            $table->boolean('is_free')->default(0)->comment('是否免费, 0:不免费|1:免费');
            $table->boolean('is_checked')->default(0)->comment('是否通过审核, 0:未通过|1:通过');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('series_id');

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
        Schema::dropIfExists('posts');
    }
}

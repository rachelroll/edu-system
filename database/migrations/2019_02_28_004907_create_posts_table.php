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

            $table->string('author')->default('')->comment('����');
            $table->tinyInteger('type')->default(1)->comment('����, 1:����|2:��Ƶ');
            $table->string('title')->default('')->comment('���±���');
            $table->string('description')->default('')->comment('��������');
            $table->text('content')->default('')->comment('��������');
            $table->string('cover')->default('')->comment('�б����ͼ');
            $table->string('pictures')->default('')->comment('��ͼ');
            $table->unsignedInteger('readed')->default(0)->comment('�Ķ���');
            $table->unsignedInteger('price')->default(0)->comment('�۸�(��)');
            $table->unsignedInteger('original_price')->default(0)->comment('ԭ��(��)');
            $table->boolean('is_free')->default(0)->comment('�Ƿ����, 0:�����|1:���');
            $table->boolean('is_checked')->default(0)->comment('�Ƿ�ͨ�����, 0:δͨ��|1:ͨ��');
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

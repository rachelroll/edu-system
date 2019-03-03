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

            $table->string('comments')->default('')->comment('����');
            $table->unsignedInteger('user_id')->default(0)->comment('�û� id');
            $table->unsignedInteger('post_id')->default(0)->comment('���� id');
            $table->string('name')->default('')->comment('������');
            $table->string('portrait')->default('')->comment('������ͷ��');

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

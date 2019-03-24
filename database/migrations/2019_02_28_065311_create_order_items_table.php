<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedInteger('order_id')->default(0)->comment('订单ID');
            $table->string('order_sn')->default('')->comment('订单编号');

            $table->unsignedInteger('user_id')->default(0)->comment('用户ID');
            $table->unsignedInteger('post_id')->default(0)->comment('教程ID');

            $table->unsignedInteger('price')->default(0)->comment('课程价格(分)');
            $table->unsignedInteger('current_price')->comment('时时价格(分)');

            $table->string('title')->default('')->comment('课程名称');
            $table->string('cover')->default('')->comment('课程封面图');

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
        Schema::dropIfExists('order_items');
    }
}

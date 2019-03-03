<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('order_sn')->unique()->comment('订单编号');
            $table->integer('post_id')->default(0)->comment('教程id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户id');

            $table->integer('title')->comment('文章标题');
            $table->unsignedInteger('total_fee')->default(0)->comment('订单总价(分)');
            $table->bigInteger('pay_log_id')->default(0)->comment('付款id');
            $table->dateTime('paid_at')->nullable()->comment('付款时间');
            $table->string('message',300)->default('')->comment('用户留言');

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
        Schema::dropIfExists('order');
    }
}

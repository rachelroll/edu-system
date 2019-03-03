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

            $table->string('order_sn')->unique()->comment('�������');
            $table->integer('post_id')->default(0)->comment('�̳�id');
            $table->unsignedInteger('user_id')->default(0)->comment('�û�id');

            $table->integer('title')->comment('���±���');
            $table->unsignedInteger('total_fee')->default(0)->comment('�����ܼ�(��)');
            $table->bigInteger('pay_log_id')->default(0)->comment('����id');
            $table->dateTime('paid_at')->nullable()->comment('����ʱ��');
            $table->string('message',300)->default('')->comment('�û�����');

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

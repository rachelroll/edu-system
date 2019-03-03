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

            $table->unsignedInteger('order_id')->default(0)->comment('����ID');
            $table->string('order_sn')->default('')->comment('�������');

            $table->unsignedInteger('user_id')->default(0)->comment('�û�ID');
            $table->unsignedInteger('post_id')->default(0)->comment('�̳�ID');

            $table->unsignedInteger('price')->default(0)->comment('�γ̼۸�(��)');
            $table->unsignedInteger('current_price')->comment('ʱʱ�۸�(��)');

            $table->string('title')->default('')->comment('�γ�����');
            $table->string('cover')->default('')->comment('�γ̷���ͼ');

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

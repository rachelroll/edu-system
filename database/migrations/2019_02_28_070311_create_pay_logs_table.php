<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pay_logs', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('appid')->default('')->comment('微信分配的公众账号ID');
            $table->string('mch_id')->default('')->comment('微信支付分配的商户号');
            $table->string('bank_type', 16)->default('')->comment('付款银行');
            $table->integer('cash_fee')->default(0)->comment('现金支付金额');
            $table->string('fee_type', 8)->default('')->comment('货币种类');
            $table->string('is_subscribe', 1)->default('')->comment('是否关注公众账号');
            $table->string('nonce_str', 32)->default('')->comment('随机字符串');
            $table->string('openid', 128)->default('')->comment('用户标识');
            $table->string('out_trade_no', 32)->default('')->comment('商户系统内部订单号');
            $table->string('result_code', 16)->default('')->comment('业务结果');
            $table->string('return_code', 16)->default('')->comment('通信标识');
            $table->string('sign', 32)->default('')->comment('签名');
            $table->string("prepay_id",64)->default('')->comment('微信生成的预支付回话标识，用于后续接口调用中使用，该值有效期为2小时');
            $table->dateTime('time_end')->nullable()->comment('支付完成时间');
            $table->integer('total_fee')->default(0)->comment('订单金额');
            $table->string('trade_type', 16)->default('')->comment('交易类型');
            $table->string('transaction_id', 32)->default('')->comment('微信支付订单号');
            $table->string('err_code', 32)->default('')->comment('错误代码');
            $table->string('err_code_des', 128)->default('')->comment('错误代码描述');
            $table->string('device_info', 32)->default('')->comment('设备号');
            $table->text('attach')->nullable()->comment('商家数据包');

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
        Schema::dropIfExists('pay_logs');
    }
}

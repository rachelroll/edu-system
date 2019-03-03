<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('course_title')->default('')->comment('ϵ�пγ���');
            $table->string('episode_name')->default('')->comment('�½�����');
            $table->unsignedInteger('parent_id')->default(0)->comment('����id, 0:�޸���, ��Ϊϵ�пγ���|����:����ϵ�пγ̵� id');

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
        Schema::dropIfExists('series');
    }
}

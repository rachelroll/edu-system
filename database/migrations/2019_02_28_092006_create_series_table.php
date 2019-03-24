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

            $table->string('course_title')->default('')->comment('系列课程名');
            $table->string('episode_name')->default('')->comment('章节名称');
            $table->unsignedInteger('parent_id')->default(0)->comment('父级id, 0:无父级, 即为系列课程名|其他:所属系列课程的 id');

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

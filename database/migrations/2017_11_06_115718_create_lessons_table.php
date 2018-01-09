<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('课程名称');
            $table->text('title')->comment('课程副标题');
            $table->tinyInteger('type')->comment('课程类型 1：免费  2：付费 3：vip');
            $table->boolean('is_top')->default(0)->comment('是否置顶 0不置顶，1置顶');
            $table->boolean('is_nav')->default(0)->comment('是否栏目推荐 0不推荐，1推荐');

            $table->integer('educational_id')->nullable()->unsigned();
            $table->foreign('educational_id')->references('id')->on('educationals')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('nav_id')->unsigned();
            $table->foreign('nav_id')->references('id')->on('navs')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('teacher_id')->unsigned();
            $table->foreign('teacher_id')->references('id')->on('teachers')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->string('play_times',50)->default(0)->comment('腾讯视频播放次数');
            $table->integer('status')->default(1)->comment('课程状态： 1：未上架， 2：已下架 ，3：已上架');
            $table->string('pictrue')->comment('课程封面');
            $table->double('price',10,2)->comment('课程价格');
            $table->text('learning')->comment('可以学到什么');
            $table->integer('like')->default(0)->comment('点赞数');
            $table->longText('for')->comment('试用人群');
            $table->longText('describle')->comment('课程描述');
            $table->softDeletes();
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
        Schema::dropIfExists('lessons');
    }
}

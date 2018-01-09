<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nickname')->comment('昵称');
            $table->string('phone')->nullable()->comment('手机');
            $table->string('openid')->comment('微信id');

            $table->integer('vip_id')->nullable()->comment('vip')->unsigned();
            $table->foreign('vip_id')->references('id')->on('vips')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->integer('auth_password_id')->nullable()->comment('auth_password')->unsigned();
            $table->foreign('auth_password_id')->references('id')->on('auth_passwords')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->string('picture', 255)->nullable()->comment('头像');
            $table->string('position')->nullable()->comment('位置');
            $table->boolean('gender')->comment('性别 true-男 false-女');
//            $table->boolean('frozen')->default(0)->comment('冻结 1:冻结 0：解冻');
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
        Schema::dropIfExists('guests');
    }
}

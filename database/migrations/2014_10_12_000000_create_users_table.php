<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('名称');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->boolean('frozen')->default(false)->comment('冻结 1:冻结 0：解冻');
            $table->boolean('gender')->default(true)->comment('性别 1-男， 2-女,0未知');
            
            $table->integer('auth_password_id')->nullable()->comment('auth_password')->unsigned();
            $table->foreign('auth_password_id')->references('id')->on('auth_passwords')
                ->onUpdate('cascade')->onDelete('cascade');
            
            $table->softDeletes();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

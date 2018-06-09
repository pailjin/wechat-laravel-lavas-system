<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePcloginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pclogin', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('scene', 45)->nullable()->comment('微信qr的scene参数');
            $table->string('openid', 100)->nullable()->comment('微信openid');
            $table->string('status', 45)->nullable()->comment('登录状态：创建，使用中，允许，已使用，超时,  拒绝');
            $table->integer('user_id')->unsigned()->comment('创建用户users表中的userid');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pclogin');
    }
}

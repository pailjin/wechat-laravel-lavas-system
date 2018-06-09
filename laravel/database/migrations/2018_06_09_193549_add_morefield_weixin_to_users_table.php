<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMorefieldWeixinToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->integer('userlevel')->default(10)->comment('用户等级：0 无效，10 普通user， 20 管理员 30 员工');
            $table->string('nickname', 45)->nullable()->comment('昵称');
            $table->string('iconurl', 500)->nullable()->comment('头像的url，一般为七牛存储的图片路径');
            $table->string('sex', 45)->nullable()->comment('性别：男，女，保密');
            $table->string('phone', 45)->nullable()->comment('电话');
            $table->json('wxinfo')->nullable()->comment('微信用户信息json格式');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('userlevel');
            $table->dropColumn('nickname');
            $table->dropColumn('iconurl');
            $table->dropColumn('sex');
            $table->dropColumn('phone');
            $table->dropColumn('wxinfo');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tokens', function(Blueprint $table) {
            $table->increments('id')->comment('サロゲートキー');
            $table->integer('user_id', 10)->unsigned()->comment('ユーザID');
            $table->string('token', 50)->comment('トークン');
            $table->integer('expire', 3)->nullable()->comment('有効期限（分）');
            $table->string('environment', 20)->comment('利用環境');
            $table->dateTime('created')->comment('生成日時');

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tokens');
    }
}

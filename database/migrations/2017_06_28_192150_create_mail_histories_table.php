<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMailHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_histories', function(Blueprint $table) {
            $table->increments('id')->comment('サロゲートキー');
            $table->integer('user_id')->unsigned()->comment('ユーザID');
            $table->string('address_to', 500)->comment('メールTO');
            $table->string('address_cc', 500)->nullable()->comment('メールCC');
            $table->string('address_bcc', 500)->nullable()->comment('メールBCC');
            $table->string('subject', 50)->nullable()->comment('メール件名');
            $table->text('body')->nullable()->comment('メール本文');
            $table->dateTime('created')->comment('初回登録日時');
            $table->dateTime('modified')->comment('更新日時');

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
        Schema::drop('mail_histories');
    }
}

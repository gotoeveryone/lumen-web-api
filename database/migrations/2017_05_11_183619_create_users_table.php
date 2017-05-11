<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            $table->increments('id')->comment('サロゲートキー');
            $table->string('account', 10)->comment('アカウント');
            $table->string('name', 20)->comment('ユーザー名');
            $table->string('password', 255)->comment('パスワード');
            $table->enum('sex', ['男性', '女性'])->comment('性別');
            $table->string('mail_address', 100)->nullable()->comment('メールアドレス');
            $table->string('sub_mail_address', 100)->nullable()->comment('サブメールアドレス');
            $table->dateTime('last_logged')->nullable()->comment('最終ログイン日時');
            $table->enum('role', ['管理者', '一般'])->comment('権限')->default('一般');
            $table->boolean('is_active')->comment('有効／無効')->default(false);
            $table->dateTime('created')->comment('初回登録日時');
            $table->string('created_by', 10)->comment('初回登録者');
            $table->dateTime('modified')->comment('最終更新日時');
            $table->string('modified_by', 10)->comment('最終更新者');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}

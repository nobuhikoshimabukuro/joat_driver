<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('m_employer_user_login_info')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('m_employer_user_login_info', function (Blueprint $table) {

            $table
                ->bigIncrements('id')
                ->comment('連番');  
                
            $table
                ->bigInteger('employer_user_id')
                ->comment('求人元ユーザーID');

            $table
                ->bigInteger('login_info_index')
                ->comment('求人元ユーザーログイン情報履歴:更新時に+1');

            $table
                ->string('employer_user_cd', 30)
                ->comment('ユーザーCD:ログイン時に入力');            

            $table
                ->string('password', 1000)
                ->comment('パスワード');            

            $table
                ->dateTime('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('作成日時:自動生成');

            $table
                ->string('created_by', 30)
                ->nullable()
                ->comment('作成者');            

            $table
                ->dateTime('deleted_at')
                ->nullable()
                ->comment('削除日時');

            $table
                ->string('deleted_by', 30)
                ->nullable()
                ->comment('削除者');
        });
        DB::statement("ALTER TABLE m_employer_user_login_info COMMENT '求人元ユーザーログイン情報'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_employer_user_login_info');
    }
};

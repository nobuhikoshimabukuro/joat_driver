<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (Schema::hasTable('m_company_user')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('m_company_user', function (Blueprint $table) {

            $table
                ->bigIncrements('id')
                ->comment('連番');  
                
            $table
                ->bigInteger('company_id')
                ->comment('会社ID');

            $table
                ->string('user_cd', 30)
                ->comment('ユーザーCD:ログイン時に入力※変更可能');

            $table
                ->integer('permission')
                ->default(0)
                ->comment('権限');

            $table
                ->string('last_name', 50)
                ->comment('姓');

            $table
                ->string('first_name', 50)
                ->comment('名');


            $table
                ->string('last_name_kana', 50)
                ->nullable()
                ->comment('姓_カナ');

            $table
                ->string('first_name_kana', 50)
                ->nullable()
                ->comment('名_カナ');

      

            $table
                ->string('mailaddress', 1000)
                ->nullable()
                ->comment('メールアドレス');

            $table
                ->string('tel', 15)
                ->nullable()
                ->comment('電話番号');

            $table
                ->string('mobile_tel', 15)
                ->nullable()
                ->comment('携帯電話番号');

            
            $table
                ->text('remarks')
                ->nullable()
                ->comment('備考');

            $table
                ->dateTime('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'))
                ->comment('作成日時:自動生成');

            $table
                ->string('created_by', 30)
                ->nullable()
                ->comment('作成者');

            $table
                ->dateTime('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))
                ->comment('更新日時:自動生成');

            $table
                ->string('updated_by', 30)
                ->nullable()
                ->comment('更新者');

            $table
                ->dateTime('deleted_at')
                ->nullable()
                ->comment('削除日時');

            $table
                ->string('deleted_by', 30)
                ->nullable()
                ->comment('削除者');
        });
        DB::statement("ALTER TABLE m_company_user COMMENT '利用会社ユーザーマスタ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_company_user');
    }
};

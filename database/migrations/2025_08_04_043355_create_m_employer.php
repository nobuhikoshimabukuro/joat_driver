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
        if (Schema::hasTable('m_employer')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('m_employer', function (Blueprint $table) {

            $table
                ->bigIncrements('employer_id')
                ->comment('連番');            

            $table
                ->string('employer_category', 1)
                ->nullable()
                ->comment('求人元区分:1=>個人事業主、2=>法人');

            $table
                ->string('corporate_number', 30)                
                ->nullable()
                ->comment('法人番号');

            $table
                ->string('employer_cd', 30)
                ->comment('求人元CD:ログイン時に入力の為、重複チェック必須');

            $table
                ->string('employer_name', 100)
                ->comment('求人元名');

            $table
                ->string('employer_name_kana', 100)
                ->nullable()
                ->comment('求人元名_カナ');           
                
            $table
                ->string('postal_code', 7)
                ->nullable()
                ->comment('郵便番号');

            $table
                ->string('address1', 100)
                ->nullable()
                ->comment('住所1');

            $table
                ->string('address2', 100)
                ->nullable()
                ->comment('住所2');
          
            $table
                ->string('address3', 100)
                ->nullable()
                ->comment('住所3');

            $table
                ->string('mailaddress1', 1000)
                ->nullable()
                ->comment('メールアドレス1');

            $table
                ->string('mailaddress2', 1000)
                ->nullable()
                ->comment('メールアドレス2');

            $table
                ->string('tel1', 15)
                ->nullable()
                ->comment('電話番号1');

            $table
                ->string('tel2', 15)
                ->nullable()
                ->comment('電話番号2');

            $table
                ->string('fax1', 15)
                ->nullable()
                ->comment('FAX1');

            $table
                ->string('fax2', 15)
                ->nullable()
                ->comment('FAX2');

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
        DB::statement("ALTER TABLE m_employer COMMENT '求人元マスタ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_employer');
    }
};

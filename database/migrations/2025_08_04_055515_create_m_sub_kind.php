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
        if (Schema::hasTable('m_sub_kind')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('m_sub_kind', function (Blueprint $table) {


            $table
                ->integer('main_kind_id')
                ->comment('大分類ID');

            $table
                ->integer('sub_kind_id')
                ->comment('中分類ID');

            $table
                ->string('sub_kind_name', 100)
                ->comment('中分類名');

            $table
                ->integer('display_order')
                ->default(1)
                ->comment('並び順:大分類毎にグルーピングする');

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

            $table->primary(['main_kind_id', 'sub_kind_id'], 'm_sub_kind_primary');
        });



        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_sub_kind COMMENT '中分類マスタ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_sub_kind');
    }
};

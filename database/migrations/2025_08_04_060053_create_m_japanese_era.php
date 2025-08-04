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
        if (Schema::hasTable('m_japanese_era')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('m_japanese_era', function (Blueprint $table) {

            $table
                ->date('start_date')
                ->comment('開始年月日');

            $table
                ->date('end_date')
                ->nullable()
                ->comment('終了年月日');

            $table
                ->string('era', 100)
                ->nullable()
                ->comment('和暦名');                    
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_japanese_era COMMENT '和暦マスタ'");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_japanese_era');
    }
};

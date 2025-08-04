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
        if (Schema::hasTable('m_tax')) {
            // テーブルが存在していればリターン
            return;
        }

        Schema::create('m_tax', function (Blueprint $table) {

            $table->bigIncrements('id')->comment('id');             
            
            $table
                ->date('start_date')
                ->comment('開始年月日');

            $table
                ->date('end_date')
                ->nullable()
                ->comment('終了年月日');

            $table
                ->decimal('tax_rate', 5, 2)
                ->nullable()
                ->comment('消費税率');                    
        });

        // ALTER 文を実行しテーブルにコメントを設定
        DB::statement("ALTER TABLE m_tax COMMENT '消費税マスタ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_tax');
    }
};

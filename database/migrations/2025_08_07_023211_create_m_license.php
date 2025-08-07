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
        if (Schema::hasTable('m_license')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('m_license', function (Blueprint $table) {

            $table
                ->bigIncrements('license_id')
                ->comment('連番');                        

            $table
                ->string('license_name', 100)
                ->comment('資格/免許名');

            $table
                ->string('license_name_kana', 100)
                ->nullable()
                ->comment('資格/免許名_カナ');           
                
            $table
                ->integer('display_order')
                ->nullable()
                ->comment('表示順');
            
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
        DB::statement("ALTER TABLE m_license COMMENT '資格/免許マスタ'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_license');
    }
};

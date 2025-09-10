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
        if (Schema::hasTable('t_job_information')) {
            // テーブルが存在していればリターン
            return;
        }
        Schema::create('t_job_information', function (Blueprint $table) {

            $table
                ->bigIncrements('job_information_id')
                ->comment('連番');

            $table
                ->bigInteger('employer_id')
                ->comment('求人元ID');            

            $table
                ->integer('publish_flg')
                ->default(0)
                ->comment('公開フラグ:0 = 公開しない、1 = 公開する');

            $table
                ->string('job_information_name', 100)                
                ->nullable()
                ->comment('求人名');

            $table
                ->string('title', 200)    
                ->nullable()            
                ->comment('求人情報タイトル');

            $table
                ->string('sub_title', 200)    
                ->nullable()            
                ->comment('求人情報名目サブタイトル');

            $table
                ->string('tel', 20)
                ->nullable()
                ->comment('応募用TEL');

            $table
                ->string('fax', 20)
                ->nullable()
                ->comment('応募用FAX');

            $table
                ->string('mailaddress', 200)
                ->nullable()
                ->comment('応募用メールアドレス');        

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

        DB::statement("ALTER TABLE t_job_information COMMENT '求人テーブル'");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_job_information');
    }
};

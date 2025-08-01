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
        if (Schema::hasTable('m_address_w')) {
            return;
        }

        Schema::create('m_address_w', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('一意識別子');
            $table->string('municipality_code', 10)->nullable()->comment('全国地方公共団体コード');
            $table->string('prefecture_code', 2)->nullable()->comment('都道府県CD');
            $table->string('old_postal_code', 5)->nullable()->comment('旧郵便番号（5桁）');
            $table->string('postal_code', 7)->nullable()->comment('郵便番号（7桁）');
            $table->string('prefecture_kana', 50)->nullable()->comment('都道府県名（カナ）');
            $table->string('city_kana', 50)->nullable()->comment('市区町村名（カナ）');
            $table->string('town_kana', 100)->nullable()->comment('町域名（カナ）');
            $table->string('prefecture', 50)->nullable()->comment('都道府県名（漢字）');
            $table->string('city', 50)->nullable()->comment('市区町村名（漢字）');
            $table->string('town', 100)->nullable()->comment('町域名（漢字）');
            $table->tinyInteger('multiple_postal_codes')->nullable()->comment('一町域が二以上の郵便番号で表される場合');
            $table->tinyInteger('subdistrict_addressing')->nullable()->comment('小字毎に番地が起番されている町域');
            $table->tinyInteger('has_chome')->nullable()->comment('丁目を有する町域');
            $table->tinyInteger('multiple_towns_per_postal')->nullable()->comment('一つの郵便番号で二以上の町域を表す場合');
            $table->tinyInteger('update_status')->nullable()->comment('更新の表示');
            $table->tinyInteger('change_reason')->nullable()->comment('変更理由');
            $table->dateTime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
        });

        DB::statement("ALTER TABLE m_address_w COMMENT '郵政提供住所データ※WorkTable'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_address_w');
    }
};

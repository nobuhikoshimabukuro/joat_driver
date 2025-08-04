<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use App\Original\DbCommon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $csvFilename = "KEN_ALL.CSV";
        DbCommon::SaveMAddress($csvFilename, true);

        DB::table('m_japanese_era')->insert([

            [
                'start_date' => '1926-12-25',
                'end_date' => '1989-01-08',
                'era' => '昭和'
            ]
            ,
            [
                'start_date' => '1989-01-09',
                'end_date' => '2019-04-30',
                'era' => '平成'
            ]
            ,
            [
                'start_date' => '2019-05-01',
                'end_date' => null,
                'era' => '令和'
            ]
        ]);
        

        DB::table('m_tax')->insert([
            [
                'start_date' => '1989-04-01',
                'end_date' => '1997-03-31',
                'tax_rate' => 3.00 // 消費税率 3%
            ],
            [
                'start_date' => '1997-04-01',
                'end_date' => '2014-03-31',
                'tax_rate' => 5.00 // 消費税率 5%
            ],
            [
                'start_date' => '2014-04-01',
                'end_date' => '2019-09-30',
                'tax_rate' => 8.00 // 消費税率 8%
            ],
            [
                'start_date' => '2019-10-01',
                'end_date' => null, // 現在適用中なので null
                'tax_rate' => 10.00 // 消費税率 10%
            ],
        ]);


        $m_main_kind_display_order = 1;
        $main_kind_id = 1;

        DB::table('m_main_kind')->insert([

            [
                'main_kind_id' => 1,
                'main_kind_name' => '雇用主種別',
                'display_order' => $m_main_kind_display_order++,
            ]
        ]);

        $m_sub_kind_display_order = 1;

        DB::table('m_sub_kind')->insert([

            [
                'main_kind_id' => $main_kind_id,
                'sub_kind_id' => 1,
                'sub_kind_name' => '個人事業主',
                'display_order' => $m_sub_kind_display_order++,
            ],
            [
                'main_kind_id' => $main_kind_id,
                'sub_kind_id' => 2,
                'sub_kind_name' => '法人',
                'display_order' => $m_sub_kind_display_order++,
            ]
        ]);

    }
}

<?php

namespace App\Original;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



// controller作成時ここからコピー↓
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyPdf as SnappyPDF;
use League\Csv\Reader;
use Illuminate\Support\Facades\File;
// controller作成時ここまでコピー↑

// Model ↓
use App\Models\MAddressModel;
use App\Models\MAddressWModel;

// Model ↑

// Request ↓

// Request ↑

class DbCommon
{    

    //target_dateはyyyy-mm-ddの形で渡すこと
    public static function get_tax_rate($target_date)
    {
        $tax_rate = 0.00;
    
        try {
            $tax_rate = m_tax_model::where('start_date', '<=', $target_date) // 開始日が $target_date 以前
                ->where(function ($query) use ($target_date) {
                    $query->where('end_date', '>=', $target_date) // 終了日が $target_date 以降
                        ->orWhereNull('end_date'); // または終了日が NULL（現在適用中）
                })
                ->orderBy('start_date', 'desc') // 開始日が最新のものを取得
                ->first()
                ->tax_rate ?? 0.00; // レコードがない場合は 0.00 を返す

        } catch (Exception $e) {
            
            
        }
    
        return $tax_rate;
    }
      
    

    //Seeder時はパブリックフォルダからcsvを取得する
    public static function SaveMAddress($csvFilenames , $SeederFlg = false)
    { 

        try {

          
            $now = now();
            
            $records = [];
    
            //以下の処理が重いので一時的にメモリ上限を無しにする
            ini_set("memory_limit", "-1");

            //同時に実行時間の制限も無しにする
            ini_set("max_execution_time",0);  
            
            
            if($SeederFlg){

                $normalContent = file_get_contents(public_path("AddressData/KEN_ALL.CSV"));
                $jigyosyoContent = file_get_contents(public_path("AddressData/JIGYOSYO.CSV"));

                if(!$normalContent){
                    throw new Exception("Seeder用CSVの読み込みに失敗");
                }

            }else{

                $normalCsvName = $csvFilenames->normalCsvName;
                $jigyosyoCsvName = $csvFilenames->jigyosyoCsvName;

                // ファイルの存在チェック
                if (!Storage::disk('AddressCsvPath')->exists($normalCsvName)) {
                    throw new Exception("都道府県CSVファイルが存在しません: " . $normalCsvName);
                }

                // Storage 経由でファイルを取得
                $normalContent = Storage::disk('AddressCsvPath')->get($normalCsvName);  
                $jigyosyoContent = Storage::disk('AddressCsvPath')->get($jigyosyoCsvName);  
            
                if(!$normalContent){
                    throw new Exception("CSVファイルの読み込みに失敗");
                }
            }            


            //m_address_w（WorkTable）を初期化後insert
            DB::statement("DELETE FROM m_address_w");
            DB::statement("ALTER TABLE m_address_w AUTO_INCREMENT = 1");

         
            //都道府県CSV   処理開始

            // **エンコーディングを自動判定**
            $encoding = mb_detect_encoding($normalContent, ['UTF-8', 'SJIS-win', 'CP932', 'EUC-JP', 'ISO-8859-1'], true);

            // **Shift-JIS または CP932 だった場合のみ UTF-8 に変換**
            if ($encoding === 'SJIS-win' || $encoding === 'CP932' || $encoding === 'SJIS') {
                $normalContent = mb_convert_encoding($normalContent, 'UTF-8', 'CP932'); // **CP932対応**
            }

            // **UTF-8 with BOM だった場合は BOM を削除**
            if ($encoding === 'UTF-8') {
                $normalContent = preg_replace('/^\xEF\xBB\xBF/', '', $normalContent);
            }
                    
            // 一時的に変換した内容をメモリにロード
            $csv = Reader::createFromString($normalContent);
            
            $csv->setDelimiter(",");
            $csv->setEnclosure('"'); // ダブルクォートで囲まれたデータを適切に処理
            $csv->setEscape('\\'); // バックスラッシュでエスケープされる場合に対応

            $csv->setHeaderOffset(null);
            $records = [];


            $normalCsvRowCount = 0;
            $batchSize = 1000; // 一括インサート用
            foreach ($csv->getRecords() as $data) {
                $normalCsvRowCount++;

                $index = 0;

                $municipality_code = $data[$index++];
                $prefecture_code = substr($municipality_code, 0, 2); // ← 都道府県コードを抽出

                $records[] = [
                    'municipality_code' => $municipality_code,
                    'prefecture_code' => $prefecture_code, 
                    'old_postal_code' => $data[$index++],
                    'postal_code' => $data[$index++],
                    'prefecture_kana' => $data[$index++],
                    'city_kana' => $data[$index++],
                    'town_kana' => $data[$index++],
                    'prefecture' => $data[$index++],
                    'city' => $data[$index++],
                    'town' => $data[$index++],
                    'multiple_postal_codes' => (int) $data[$index++],
                    'subdistrict_addressing' => (int) $data[$index++],
                    'has_chome' => (int) $data[$index++],
                    'multiple_towns_per_postal' => (int) $data[$index++],
                    'update_status' => (int) $data[$index++],
                    'change_reason' => (int) $data[$index++],
                    'created_at' => $now,
                ];

                // バッチサイズごとに挿入
                if (count($records) >= $batchSize) {
                    MAddressWModel::insert($records);
                    $records = []; // メモリ解放
                }
            }

            // 残りのデータを挿入
            if (!empty($records)) {
                MAddressWModel::insert($records);
            }
            //都道府県CSV   処理終了



            //事業所CSV   処理開始
            
            // **エンコーディングを自動判定**
            $encoding = mb_detect_encoding($jigyosyoContent, ['UTF-8', 'SJIS-win', 'CP932', 'EUC-JP', 'ISO-8859-1'], true);

            // **Shift-JIS または CP932 だった場合のみ UTF-8 に変換**
            if ($encoding === 'SJIS-win' || $encoding === 'CP932' || $encoding === 'SJIS') {
                $jigyosyoContent = mb_convert_encoding($jigyosyoContent, 'UTF-8', 'CP932'); // **CP932対応**
            }

            // **UTF-8 with BOM だった場合は BOM を削除**
            if ($encoding === 'UTF-8') {
                $jigyosyoContent = preg_replace('/^\xEF\xBB\xBF/', '', $jigyosyoContent);
            }
                    
            // 一時的に変換した内容をメモリにロード
            $csv = Reader::createFromString($jigyosyoContent);
            
            $csv->setDelimiter(",");
            $csv->setEnclosure('"'); // ダブルクォートで囲まれたデータを適切に処理
            $csv->setEscape('\\'); // バックスラッシュでエスケープされる場合に対応

            $csv->setHeaderOffset(null);
            $records = [];

            $jigyosyoCsvRowCount = 0;
            $batchSize = 1000; // 一括インサート用
            $records = [];

            foreach ($csv->getRecords() as $data) {
                $jigyosyoCsvRowCount++;
            
                // 列をすべて正しく定義する
                $municipality_code  = $data[0];  // 自治体コード
                $corporation_kana   = $data[1];  // 法人名カナ
                $corporation_name   = $data[2];  // 法人名
                $prefecture         = $data[3];  // 都道府県
                $city               = $data[4];  // 市区町村
                $town_name          = $data[5];  // 町域
                $extra_info         = $data[6];  // 補足（建物名など）
                $postal_code        = $data[7];  // 郵便番号
            
                $prefecture_code = substr($municipality_code, 0, 2);
            
                $town = "{$town_name}【{$corporation_name} {$extra_info}】";
            
                // // postal_codeが7文字超えたらtruncate
                // $postal_code = substr($postal_code, 0, 7);
            
                $records[] = [
                    'municipality_code'       => $municipality_code,
                    'prefecture_code'         => $prefecture_code,
                    'old_postal_code'         => '', // 空
                    'postal_code'             => $postal_code,
                    'prefecture_kana'         => '',
                    'city_kana'               => '',
                    'town_kana'               => '',
                    'prefecture'              => $prefecture,
                    'city'                    => $city,
                    'town'                    => $town,
                    'multiple_postal_codes'   => 0,
                    'subdistrict_addressing'  => 0,
                    'has_chome'               => 0,
                    'multiple_towns_per_postal' => 0,
                    'update_status'           => 0,
                    'change_reason'           => 0,
                    'created_at'              => $now,
                ];
            
                if (count($records) >= $batchSize) {
                    MAddressWModel::insert($records);
                    $records = [];
                }
            }

            // 残りのデータを挿入
            if (!empty($records)) {
                MAddressWModel::insert($records);
            }
            //事業所CSV   処理終了


            //CSVの行数とm_address_wのレコード数一致するか確認

            // CSVの行数取得（ヘッダーなし前提）
            $csvRowCount = $normalCsvRowCount + $jigyosyoCsvRowCount;

            // Workテーブルの件数を取得
            $workCount = MAddressWModel::count();

            // 件数チェック
            if ($csvRowCount !== $workCount) {
                throw new Exception("
                CSV行数とWorkテーブルの件数が一致しません。                
                normalCsvRowCount: {$normalCsvRowCount},jigyosyoCsvRowCount: {$jigyosyoCsvRowCount}, Work: {$workCount}
                ");
            }



            //CSVの行数とm_address_wのレコード数一致した場合は、m_addressを初期化して、m_address_wからデータを移行
           // 件数が一致すればトランザクションで本テーブルへ移行            
            try {
                // 本テーブル初期化
                DB::statement("DELETE FROM m_address");
                DB::statement("ALTER TABLE m_address AUTO_INCREMENT = 1");

                // WorkテーブルからINSERT
                DB::statement("
                    INSERT INTO m_address (
                        municipality_code, prefecture_code, old_postal_code, postal_code,
                        prefecture_kana, city_kana, town_kana,
                        prefecture, city, town,
                        multiple_postal_codes, subdistrict_addressing, has_chome,
                        multiple_towns_per_postal, update_status, change_reason, created_at
                    )
                    SELECT
                        municipality_code, prefecture_code, old_postal_code, postal_code,
                        prefecture_kana, city_kana, town_kana,
                        prefecture, city, town,
                        multiple_postal_codes, subdistrict_addressing, has_chome,
                        multiple_towns_per_postal, update_status, change_reason, created_at
                    FROM m_address_w
                    ORDER BY 
                        prefecture_code,
                        municipality_code,
                        town_kana = '' OR town_kana IS NULL,
                        postal_code
                ");

    
                $areaInfo = self::GetAreaInfo();

                foreach ($areaInfo as $Info) {

                    $prefecture_code = $Info->prefecture_code;
                    $area_code = $Info->area_code;
                    $area_name = $Info->area_name;                   

                    MAddressModel::
                    where('prefecture_code', $prefecture_code)							
                    ->update(
                        [
                            'area_code' => $area_code,	
                            'area_name' => $area_name,	
                        ]
                    );

                    
                }


                
                if(MAddressModel::count() == MAddressWModel::count()){
                    return true;
                }else{
                    return false;
                }
                

            } catch (Exception $e) {
                
                throw new Exception("m_addressへの移行処理で失敗しました: " . $e->getMessage());
            }            

    
        } catch (Exception $e) {
            
            $error_message = $e->getMessage();

          
            Log::channel('speedchk_log_fsi')->info("【PostalCodeManagementController】:insert".$error_message);

            return false;
    
            
        }

    }



    //伝票番号から請求書発行済みか確認
    public static function GetAreaInfo()
    {

        $areas = [];

        // 北海道地方
        $areas[] = (object)[ "prefecture_code" => "01", "area_code" => "01", "area_name" => "北海道地方" ];

        // 東北地方
        $areas[] = (object)[ "prefecture_code" => "02", "area_code" => "02", "area_name" => "東北地方" ];
        $areas[] = (object)[ "prefecture_code" => "03", "area_code" => "02", "area_name" => "東北地方" ];
        $areas[] = (object)[ "prefecture_code" => "04", "area_code" => "02", "area_name" => "東北地方" ];
        $areas[] = (object)[ "prefecture_code" => "05", "area_code" => "02", "area_name" => "東北地方" ];
        $areas[] = (object)[ "prefecture_code" => "06", "area_code" => "02", "area_name" => "東北地方" ];
        $areas[] = (object)[ "prefecture_code" => "07", "area_code" => "02", "area_name" => "東北地方" ];

        // 関東地方
        $areas[] = (object)[ "prefecture_code" => "08", "area_code" => "03", "area_name" => "関東地方" ];
        $areas[] = (object)[ "prefecture_code" => "09", "area_code" => "03", "area_name" => "関東地方" ];
        $areas[] = (object)[ "prefecture_code" => "10", "area_code" => "03", "area_name" => "関東地方" ];
        $areas[] = (object)[ "prefecture_code" => "11", "area_code" => "03", "area_name" => "関東地方" ];
        $areas[] = (object)[ "prefecture_code" => "12", "area_code" => "03", "area_name" => "関東地方" ];
        $areas[] = (object)[ "prefecture_code" => "13", "area_code" => "03", "area_name" => "関東地方" ];
        $areas[] = (object)[ "prefecture_code" => "14", "area_code" => "03", "area_name" => "関東地方" ];

        // 中部地方
        $areas[] = (object)[ "prefecture_code" => "15", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "16", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "17", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "18", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "19", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "20", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "21", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "22", "area_code" => "04", "area_name" => "中部地方" ];
        $areas[] = (object)[ "prefecture_code" => "23", "area_code" => "04", "area_name" => "中部地方" ];

        // 近畿地方
        $areas[] = (object)[ "prefecture_code" => "24", "area_code" => "05", "area_name" => "近畿地方" ];
        $areas[] = (object)[ "prefecture_code" => "25", "area_code" => "05", "area_name" => "近畿地方" ];
        $areas[] = (object)[ "prefecture_code" => "26", "area_code" => "05", "area_name" => "近畿地方" ];
        $areas[] = (object)[ "prefecture_code" => "27", "area_code" => "05", "area_name" => "近畿地方" ];
        $areas[] = (object)[ "prefecture_code" => "28", "area_code" => "05", "area_name" => "近畿地方" ];
        $areas[] = (object)[ "prefecture_code" => "29", "area_code" => "05", "area_name" => "近畿地方" ];
        $areas[] = (object)[ "prefecture_code" => "30", "area_code" => "05", "area_name" => "近畿地方" ];

        // 中国地方
        $areas[] = (object)[ "prefecture_code" => "31", "area_code" => "06", "area_name" => "中国地方" ];
        $areas[] = (object)[ "prefecture_code" => "32", "area_code" => "06", "area_name" => "中国地方" ];
        $areas[] = (object)[ "prefecture_code" => "33", "area_code" => "06", "area_name" => "中国地方" ];
        $areas[] = (object)[ "prefecture_code" => "34", "area_code" => "06", "area_name" => "中国地方" ];
        $areas[] = (object)[ "prefecture_code" => "35", "area_code" => "06", "area_name" => "中国地方" ];

        // 四国地方
        $areas[] = (object)[ "prefecture_code" => "36", "area_code" => "07", "area_name" => "四国地方" ];
        $areas[] = (object)[ "prefecture_code" => "37", "area_code" => "07", "area_name" => "四国地方" ];
        $areas[] = (object)[ "prefecture_code" => "38", "area_code" => "07", "area_name" => "四国地方" ];
        $areas[] = (object)[ "prefecture_code" => "39", "area_code" => "07", "area_name" => "四国地方" ];

        // 九州・沖縄地方
        $areas[] = (object)[ "prefecture_code" => "40", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "41", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "42", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "43", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "44", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "45", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "46", "area_code" => "08", "area_name" => "九州・沖縄地方" ];
        $areas[] = (object)[ "prefecture_code" => "47", "area_code" => "08", "area_name" => "九州・沖縄地方" ];


        return $areas;
    }

        
}

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
    public static function SaveMAddress($csvFilename , $SeederFlg = false)
    { 

        try {

          
            $now = now();
            
            $records = [];
    
            //以下の処理が重いので一時的にメモリ上限を無しにする
            ini_set("memory_limit", "-1");

            //同時に実行時間の制限も無しにする
            ini_set("max_execution_time",0);  
            
            
            if($SeederFlg){

                $csvContent = file_get_contents(public_path("AddressData/{$csvFilename}"));

                if(!$csvContent){
                    throw new Exception("Seeder用CSVの読み込みに失敗");
                }

            }else{

                // ファイルの存在チェック
                if (!Storage::disk('AddressCsvPath')->exists($csvFilename)) {
                    throw new Exception("CSVファイルが存在しません: " . $csvFilename);
                }

                // Storage 経由でファイルを取得
                $csvContent = Storage::disk('AddressCsvPath')->get($csvFilename);  
            
                if(!$csvContent){
                    throw new Exception("CSVファイルの読み込みに失敗");
                }
            }            
         

            // **エンコーディングを自動判定**
            $encoding = mb_detect_encoding($csvContent, ['UTF-8', 'SJIS-win', 'CP932', 'EUC-JP', 'ISO-8859-1'], true);

            // **Shift-JIS または CP932 だった場合のみ UTF-8 に変換**
            if ($encoding === 'SJIS-win' || $encoding === 'CP932' || $encoding === 'SJIS') {
                $csvContent = mb_convert_encoding($csvContent, 'UTF-8', 'CP932'); // **CP932対応**
            }

            // **UTF-8 with BOM だった場合は BOM を削除**
            if ($encoding === 'UTF-8') {
                $csvContent = preg_replace('/^\xEF\xBB\xBF/', '', $csvContent);
            }
            
        
            // 一時的に変換した内容をメモリにロード
            $csv = Reader::createFromString($csvContent);
            
            $csv->setDelimiter(",");
            $csv->setEnclosure('"'); // ダブルクォートで囲まれたデータを適切に処理
            $csv->setEscape('\\'); // バックスラッシュでエスケープされる場合に対応


            //m_address_w（WorkTable）を初期化後insert
            DB::statement("DELETE FROM m_address_w");
            DB::statement("ALTER TABLE m_address_w AUTO_INCREMENT = 1");
            

            $csv->setHeaderOffset(null);
            $records = [];

            $batchSize = 1000; // 一括インサート用
            foreach ($csv->getRecords() as $data) {
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
           
            //CSVの行数とm_address_wのレコード数一致するか確認

            // CSVの行数取得（ヘッダーなし前提）
            $csvRowCount = iterator_count($csv->getRecords());

            // Workテーブルの件数を取得
            $workCount = MAddressWModel::count();

            // 件数チェック
            if ($csvRowCount !== $workCount) {
                throw new Exception("CSV行数とWorkテーブルの件数が一致しません。CSV: {$csvRowCount}, Work: {$workCount}");
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
                ");
                

                if(MAddressModel::count() == MAddressWModel::count()){
                    return true;
                }else{
                    return false;
                }
                

            } catch (Exception $e) {
                
                throw new Exception("m_addressへの移行処理で失敗しました: " . $e->getMessage());
            }
   
            return true;

    
        } catch (Exception $e) {
            
            $error_message = $e->getMessage();

          
            Log::channel('speedchk_log_fsi')->info("【PostalCodeManagementController】:insert".$error_message);

            return false;
    
            
        }

    }



    //伝票番号から請求書発行済みか確認
    public static function check_billing_info($slip_number)
    {

        try {
            $result = false;
			
            // 伝票番号がセットされている場合は発行済みと判断
            $data = t_billing_details_model::where("slip_number", $slip_number)							
							->whereRaw("COALESCE(billing_number, '') <> ''")    
                            ->first();
            
            //データ存在時は、請求書発行済みと判断
            if(!is_null($data)){
                $result = true;
            }
            
							
            


		} catch (Exception $e) {
			
		}


        return $result;
    }

        
}

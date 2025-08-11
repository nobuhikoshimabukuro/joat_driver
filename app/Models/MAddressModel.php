<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAddressModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_address';    
    protected $primaryKey = 'id';  

    public static function GetPrefectureInfo()
    {
        return self::select('prefecture_code', 'prefecture', 'prefecture_kana')
        ->groupBy('prefecture_code', 'prefecture', 'prefecture_kana')
        ->orderBy('prefecture_code')
        ->get();
    }


    //郵便番号から住所選定
	public static function GetAddressForPostalCode($postal_code)
	{
	
		// ハイフンを除去（全角・半角どちらも対応）
		$postal_code = str_replace([' ','　','-', '－'], '', $postal_code);
		$address = "";
				
		try {
		
			$m_address = self::			
			where("postal_code", $postal_code)			
			->first();

			if (!is_null($m_address)) {
				$address = "{$m_address->prefecture}{$m_address->city}{$m_address->town}";
			}						

		} catch (Exception $e) {

			Log::channel('db_access_log')->error("[check_post_number] {$e->getMessage()}");
		}

		return $address;

	}


    public static function GetPostalCodeForAddress($address)
	{

		// スペースを除去（全角・半角どちらも対応）
		$address = str_replace([' ', '　'], '', $address);
		$return_postal_code = "";
		
		try{

			// 郵便番号を推定（最大10回まで文字を追加）
			for ($i = 0; $i < 10; $i++) {

				$index = 5 + $i;
				
				if (mb_strlen($address) < $index) {
					$search_address = $address;					
				}else{
					$search_address = mb_substr($address, 0,$index);
				}
				
				// WHERE検索
				$postal_code_info_first = self::select('postal_code')
				->selectRaw("CONCAT(prefecture, city, town) AS full_address")
				->whereRaw("CONCAT(prefecture, city, town) = ?", ["{$search_address}"])					
				->first();

				if(!is_null($postal_code_info_first)){
					$return_postal_code = $postal_code_info_first->postal_code;
					break;
				}			

				// LIKE検索
				$postal_code_info = self::select('postal_code')
					->selectRaw("CONCAT(prefecture, city, town) AS full_address")
					->whereRaw("CONCAT(prefecture, city, town) LIKE ?", ["{$search_address}%"])
					->limit(2)
					->get();

				if ($postal_code_info->count() === 1) {
					$return_postal_code = $postal_code_info[0]->postal_code;
					break;
				}
						
				

				if ($postal_code_info->count() === 0) {
					break; // 該当なしで中断
				}
			}
			
		
		} catch (Exception $e) {
			
		}

		return $return_postal_code;

	}

}

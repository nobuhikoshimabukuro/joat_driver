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
}

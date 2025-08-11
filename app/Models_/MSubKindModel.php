<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MSubKindModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_sub_kind';    
    protected $primaryKey = ["main_kind_id","sub_kind_id"]; 

    // 複合プライマリキー はこれが絶対必要
    public $incrementing = false;

    protected $fillable = [
        'main_kind_id',
        'sub_kind_id',
        'sub_kind_name',
        'display_order',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by'
    ];

    /**
     * 指定された main_kind_id で取得（削除済みデータは除外）
     *
     * @param string $main_kind_id     
     */
    public static function GetData($main_kind_id)
    {
        $m_sub_kind = self::where('main_kind_id', $main_kind_id)
            ->whereNull('deleted_at') // 削除されていないデータだけ
            ->orderBy('display_order', 'asc')
            ->select([
                'sub_kind_id as value',
                'sub_kind_name as display'
            ])
            ->get();
    
        return $m_sub_kind;
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MEmployerModel extends Model
{
    use SoftDeletes;    
    protected $connection = 'mysql';
    protected $table = 'm_employer';    
    protected $primaryKey = 'employer_id';  

 
    public static function getEmployerCategories(): array
    {
        return [
            (object)["value" => "1", "display" => "個人事業主"],
            (object)["value" => "2", "display" => "法人"],
        ];
    }


}

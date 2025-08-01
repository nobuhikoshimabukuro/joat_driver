<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MCompanyModel extends Model
{
    use SoftDeletes;    
    protected $connection = 'mysql';
    protected $table = 'm_company';    
    protected $primaryKey = 'company_id';  


}

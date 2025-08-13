<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MLicenseMmodel extends Model
{
    use SoftDeletes;    
    protected $connection = 'mysql';
    protected $table = 'm_license';    
    protected $primaryKey = 'license_id'; 
}

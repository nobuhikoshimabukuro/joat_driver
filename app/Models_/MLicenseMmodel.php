<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MLicenseMmodel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_license';    
    protected $primaryKey = 'license_id'; 
}

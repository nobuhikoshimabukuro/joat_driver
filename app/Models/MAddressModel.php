<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MAddressModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_address';    
    protected $primaryKey = 'id';  
}

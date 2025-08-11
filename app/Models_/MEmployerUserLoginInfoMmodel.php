<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MEmployerUserLoginInfoMmodel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_employer_user_login_info';    
    protected $primaryKey = 'id'; 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MManagerUserLoginInfoMmodel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_manager_user_login_info';    
    protected $primaryKey = 'id'; 
}

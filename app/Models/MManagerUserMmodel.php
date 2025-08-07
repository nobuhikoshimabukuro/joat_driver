<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MManagerUserMmodel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_manager_user';    
    protected $primaryKey = 'manager_user_id'; 
}

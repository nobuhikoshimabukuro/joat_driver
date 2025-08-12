<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MManagerUserModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_manager_user';    
    protected $primaryKey = 'user_id'; 
}

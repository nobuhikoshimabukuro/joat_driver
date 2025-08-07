<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MEmployerUserMmodel extends Model
{
    protected $connection = 'mysql';
    protected $table = 'm_employer_user';    
    protected $primaryKey = 'id'; 
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MMainKindModel extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'm_main_kind';
    protected $primaryKey = 'main_kind_id';
}

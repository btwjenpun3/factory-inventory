<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpTable extends Model
{
    use HasFactory;

    protected $table = 'kp_tbl';

    protected $primaryKey = 'id'; 

    protected $guarded = ['id'];

    public $timestamps = false;
}

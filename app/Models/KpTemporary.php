<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KpTemporary extends Model
{
    use HasFactory;

    protected $table = 'web_kp_temporary';

    protected $primaryKey = 'id'; 

    protected $guarded = ['id'];

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rak extends Model
{
    use HasFactory;

    protected $table = 'master_rak';

    protected $primaryKey = 'id_rak'; 

    protected $guarded = ['id_rak'];

    public $timestamps = false;
}

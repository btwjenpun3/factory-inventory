<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseRequest extends Model
{
    use HasFactory;

    protected $table = 'req';

    protected $primaryKey = 'id_req'; 

    protected $protected = [
        'id_req'
    ];

    public $timestamps = false;
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $table = 'item';

    protected $primaryKey = 'id_item';

    protected $fillable = [
        'code_buyer', 'items', 'desc'
    ];

    public $timestamps = false;
}

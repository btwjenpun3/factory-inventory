<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    use HasFactory;

    protected $table = 'buyer';

    protected $guarded = ['id_buyer'];

    protected $primaryKey = 'code';

    public $incrementing = false;
    
    protected $keyType = 'string';

    public $timestamps = false;
}

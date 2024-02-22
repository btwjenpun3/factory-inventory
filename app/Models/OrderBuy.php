<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBuy extends Model
{
    use HasFactory;

    protected $table = 'order_buy';

    protected $primaryKey = 'id'; 

    protected $guarded = ['id'];

    public $timestamps = false;
}

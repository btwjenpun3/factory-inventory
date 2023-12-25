<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kp extends Model
{
    use HasFactory;

    protected $table = 'kp';

    protected $primaryKey = 'no'; 

    protected $protected = [
        'no'
    ];

    public $timestamps = false;
}

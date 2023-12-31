<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $table = 'web_permissions';

    protected $primaryKey = 'id'; 

    protected $fillable = [
        'permission'
    ];

    public $timestamps = false; 

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

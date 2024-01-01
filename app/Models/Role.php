<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'web_roles';

    protected $primaryKey = 'id'; 

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;    

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'web_role_permissions', 'role_id', 'permission_id');
    }
}

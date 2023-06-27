<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $table = 'role';
    protected $keyType = 'string';
    protected $primaryKey = 'id_role';
    protected $fillable = [
        'id_role',
        'nama_role',
    ];
}

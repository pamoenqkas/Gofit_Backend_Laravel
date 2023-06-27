<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instruktur extends Model
{
    use HasFactory;
    
    protected $table = 'instruktur';
    protected $primaryKey = 'id_instruktur';
    protected $keyType = 'string';
    protected $fillable = [
        'id_instruktur',
        'nama_instruktur',
        'no_telp_instruktur',
        'alamat_instruktur',
        'email_instruktur',
        'tanggal_lahir_instruktur',
        'password',
        'total_terlambat'
    ];

    public function jadwal_harian(){
        return $this->hasMany(member::class, 'id_member', 'id');
    }

    public function jadwal_umum(){
        return $this->hasMany(member::class, 'id_member', 'id');
    }
}

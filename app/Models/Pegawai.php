<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    protected $table = 'pegawai';
    protected $keyType = 'string';
    protected $primaryKey = 'id_pegawai';
    protected $fillable = [
        'id_pegawai',
        'id_role',
        'nama_pegawai',
        'no_telp_pegawai',
        'alamat_pegawai',
        'email_pegawai',
        'tanggal_lahir_pegawai',
        'password',
    ];

    public function aktivasi_tahunan(){
        return $this->hasMany(member::class, 'id_member', 'id');
    }

    public function deposit_umum(){
        return $this->hasMany(member::class, 'id_member', 'id');
    }

    public function deposit_kelas(){
        return $this->hasMany(member::class, 'id_member', 'id');
    }
}


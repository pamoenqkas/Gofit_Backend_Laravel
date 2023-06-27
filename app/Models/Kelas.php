<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    
    protected $table = 'kelas';
    protected $keyType = 'string';
    protected $primaryKey = 'id_kelas';
    protected $fillable = [
        'id_kelas',
        'nama_kelas',
        'harga',
        'kapasitas',
    ];

    public function jadwalHarian()
    {
        return $this->hasMany(JadwalHarian::class, 'id_kelas');
    }

    public function jadwalUmum()
    {
        return $this->hasMany(JadwalUmum::class, 'id_kelas');
    }
}

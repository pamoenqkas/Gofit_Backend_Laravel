<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Izin extends Model
{
    use HasFactory;

    protected $table = 'izin';
    protected $primaryKey = 'id_izin';
    protected $keyType = 'string';
    protected $fillable = [
        'id_izin',
        'id_instruktur',
        'id_jadwal_harian',
        'id_instruktur_pengganti',
        'tanggal',
        'deskripsi_izin',
        'status',
    ];

    public function instruktur(){
        return $this->belongsTo(instruktur::class, 'id_instruktur');
    }

    public function jadwal_harian(){
        return $this->belongsTo(JadwalHarian::class, 'id_jadwal_harian');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiInstruktur extends Model
{
    use HasFactory;
    
    protected $table = 'presensi_instruktur';
    protected $primaryKey = 'id_presensi_instruktur';
    protected $keyType = 'string';
    protected $fillable = [
        'id_presensi_instruktur',
        'id_jadwal_harian',
        'jam_mulai',
        'jam_selesai',
        'keterlambatan',
        'kehadiran',
    ];

    public function jadwal_harian(){
        return $this->belongsTo(JadwalHarian::class, 'id_jadwal_harian');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }

}

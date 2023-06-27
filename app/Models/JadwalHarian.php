<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalHarian extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'jadwal_harian';
    protected $keyType = 'string';
    protected $primaryKey = 'id_jadwal_harian';
    protected $fillable = [
        'id_jadwal_harian',
        'id_kelas',
        'id_instruktur',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'tanggal',
        'keterangan',
    ];

    public function instruktur(){
        return $this->belongsTo(Instruktur::class, 'id_instruktur');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    
    public function presensi_instruktur()
    {
        return $this->hasMany(PresensiInstruktur::class, 'id_jadwal_harian');
    }

    public function presensi_kelas()
    {
        return $this->hasMany(PresensiKelas::class, 'id_jadwal_harian');
    }

    public function izin()
    {
        return $this->hasMany(Izin::class, 'id_jadwal_harian');
    }
}

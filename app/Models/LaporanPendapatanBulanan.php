<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanPendapatanBulanan extends Model
{
    use HasFactory;
    
    protected $table = 'laporan_pendapatan_bulanan';
    protected $primaryKey = 'id_laporan_pendapatan_bulanan';
    protected $keyType = 'string';
    protected $fillable = [
        'id_laporan_pendapatan_bulanan',
        'periode',
        'tanggal_cetak',
        'bulan',
        'aktivasi',
        'deposit',
        'total',
    ];

    // public function jadwal_harian(){
    //     return $this->belongsTo(JadwalHarian::class, 'id_jadwal_harian');
    // }

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class, 'id_kelas');
    // }

    public function tableBs()
    {
        return $this->hasMany(AktivasiTahunan::class);
    }


}

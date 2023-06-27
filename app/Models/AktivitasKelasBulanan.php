<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasKelasBulanan extends Model
{
    use HasFactory;

    protected $table = 'aktivitas_kelas_bulanan';
    protected $primaryKey = 'id_aktivitas_kelas_bulanan';
    protected $keyType = 'string';
    protected $fillable = [
        'id_aktivitias_kelas_bulanan',
        'bulan',
        'tahun',
        'tanggal_cetak',
        'kelas',
        'instruktur',
        'jumlah_peserta',
        'jumlah_libur',
    ];

}

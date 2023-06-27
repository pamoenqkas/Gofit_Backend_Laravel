<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KinerjaInstrukturBulanan extends Model
{
    use HasFactory;

    protected $table = 'kinerja_instruktur_bulanan';
    protected $primaryKey = 'id_kinerja_instruktur_bulanan';
    protected $keyType = 'string';
    protected $fillable = [
        'id_kinerja_instruktur_bulanan',
        'bulan',
        'tahun',
        'tanggal_cetak',
        'nama',
        'jumlah_hadir',
        'jumlah_libur',
        'total',
    ];
}

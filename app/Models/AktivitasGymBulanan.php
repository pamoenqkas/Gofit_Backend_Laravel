<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivitasGymBulanan extends Model
{
    use HasFactory;
    
    protected $table = 'aktivitas_gym_bulanan';
    protected $primaryKey = 'id_aktivitas_gym_bulanan';
    protected $keyType = 'string';
    protected $fillable = [
        'id_aktivitas_gym_bulanan',
        'bulan',
        'tahun',
        'tanggal_cetak',
        'tanggal',
        'jumlah_member',
        'total',
    ];

}

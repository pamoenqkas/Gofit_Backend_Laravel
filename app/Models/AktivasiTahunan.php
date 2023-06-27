<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AktivasiTahunan extends Model
{
    use HasFactory;
    
    protected $table = 'aktivasi_tahunan';
    protected $primaryKey = 'id_aktivasi_tahunan';
    protected $keyType = 'string';
    protected $fillable = [
        'id_aktivasi_tahunan',
        'id_pegawai',
        'id_member',
        'tanggal',
        'masa_aktif',
        'aktivasi_tahunan',
    ];

    public function member(){
        return $this->belongsTo(member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }

    //Untuk expire_date

    public function setExpireDate()
    {
        $this->masa_aktif = $this->created_at->addYear();
    }

    public function tableA()
    {
        return $this->belongsTo(LaporanPendapatanBulanan::class);

    }
}

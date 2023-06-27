<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositKelas extends Model
{
    use HasFactory;

    protected $table = 'deposit_kelas';
    protected $primaryKey = 'id_deposit_kelas';
    protected $keyType = 'string';
    protected $fillable = [
        'id_deposit_kelas',
        'id_member',
        'id_pegawai',
        'id_promo_kelas',
        'tanggal',
        'deposit_kelas',
        'jenis_kelas',
        'total_deposit',
        'masa_berlaku',
    ];

    public function member(){
        return $this->belongsTo(member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }
}

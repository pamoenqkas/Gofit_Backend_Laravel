<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositUmum extends Model
{
    use HasFactory;
    
    protected $table = 'deposit_umum';
    protected $primaryKey = 'id_deposit_umum';
    protected $keyType = 'string';
    protected $fillable = [
        'id_deposit_umum',
        'id_pegawai',
        'id_member',
        'id_promo_umum',
        'tanggal',
        'deposit',
        'total_deposit',
        'bonus_deposit',
        'sisa_deposit',
    ];

    public function member(){
        return $this->belongsTo(member::class, 'id_member');
    }

    public function pegawai(){
        return $this->belongsTo(pegawai::class, 'id_pegawai');
    }
}

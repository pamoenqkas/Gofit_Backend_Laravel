<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoKelas extends Model
{
    use HasFactory;

    protected $table = 'promo_kelas';
    protected $keyType = 'string';
    protected $primaryKey = 'id_promo_kelas';
    protected $fillable = [
        'id_promo_kelas',
        'syarat_bonus_kelas',
        'bonus_kelas',
    ];
}

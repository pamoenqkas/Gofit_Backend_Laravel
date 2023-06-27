<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoUmum extends Model
{
    use HasFactory;

    protected $table = 'promo_umum';
    protected $keyType = 'string';
    protected $primaryKey = 'id_promo_umum';
    protected $fillable = [
        'id_promo_umum',
        'syarat_bonus_umum',
        'bonus_umum',
    ];
}

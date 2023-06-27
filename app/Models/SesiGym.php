<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesiGym extends Model
{
    protected $table = 'sesi_gym';
    protected $keyType = 'string';
    protected $primaryKey = 'id_sesi';
    protected $fillable = [
        'id_sesi',
        'jam_mulai',
        'jam-selesai',
        'kuota',
    ];

    public function booking_gym(){
        return $this->hasMany(BookingGym::class, 'id_booking_gym');
    }
}

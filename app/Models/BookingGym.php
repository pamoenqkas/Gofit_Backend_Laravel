<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingGym extends Model
{
    use HasFactory;

    protected $table = 'booking_gym';
    protected $primaryKey = 'id_booking_gym';
    protected $keyType = 'string';
    protected $fillable = [
        'id_booking_gym',
        'id_member',
        'id_sesi',
        'tanggal_booking_gym',
        'kehadiran',
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function sesi_gym(){
        return $this->belongsTo(SesiGym::class, 'id_sesi');
    }
    
}

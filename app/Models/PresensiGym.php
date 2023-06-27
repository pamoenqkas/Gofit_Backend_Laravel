<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiGym extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'presensi_gym';
    protected $keyType = 'string';
    protected $primaryKey = 'id_presensi_gym';
    protected $fillable = [
        'id_presensi_gym',
        'id_member',
        'id_booking_gym',
        'status',
        'tanggal_presensi_gym'
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function booking_gym(){
        return $this->belongsTo(BookingGym::class, 'id_booking_gym');
    }

    public function sesi_gym()
    {
        return $this->belongsTo(SesiGym::class, 'id_sesi');
    }
}

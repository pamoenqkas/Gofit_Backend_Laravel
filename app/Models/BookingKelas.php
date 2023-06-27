<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingKelas extends Model
{
    use HasFactory;

    protected $table = 'booking_kelas';
    protected $primaryKey = 'id_booking_kelas';
    protected $keyType = 'string';
    protected $fillable = [
        'id_booking_kelas',
        'id_member',
        'id_jadwal_harian',
        'id_kelas',
        'tanggal_booking_kelas',
        'metode_pembayaran',
    ];

    public function member(){
        return $this->belongsTo(member::class, 'id_member');
    }

    public function jadwal_harian()
    {
        return $this->belongsTo(JadwalHarian::class, 'id_jadwal_harian');
    }

    public function kelas(){
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }


    //Untuk expire_date

    // public function setExpireDate()
    // {
    //     $this->masa_aktif = $this->created_at->addYear();
    // }
}

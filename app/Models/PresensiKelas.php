<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiKelas extends Model
{
    use HasFactory;
    /**
     * fillable
     *
     * @var array
     */
    protected $table = 'presensi_kelas';
    protected $keyType = 'string';
    protected $primaryKey = 'id_presensi_kelas';
    protected $fillable = [
        'id_presensi_kelas',
        'id_booking_kelas',
        'id_member',        
        'tanggal_presensi_kelas',
        'status',
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'id_member');
    }

    public function jadwal_harian(){
        return $this->belongsTo(JadwalHarian::class, 'id_jadwal_harian');
    }

    public function booking_kelas()
    {
        return $this->belongsTo(BookingKelas::class, 'id_booking_kelas');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas');
    }
    

    // public function kelas()
    // {
    //     return $this->belongsTo(Kelas::class, 'id_kelas');
    // }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;
    
    protected $table = 'member';
    protected $keyType = 'string';
    protected $primaryKey = 'id_member';
    protected $fillable = [
        'id_member',
        'nama_member',
        'no_telp_member',
        'alamat_member',
        'email_member',
        'tanggal_lahir',
        'deposit',
        'deposit_kelas',
        'masa_membership',
        'tanggal_daftar',
        'status',
        'password',
        'masa_berlaku_kelas',
    ];

    public function aktivasi_tahunan(){
        return $this->hasMany(aktivasi_tahunan::class, 'id_member', 'id');
    }

    public function deposit_umum(){
        return $this->hasMany(deposit_umum::class, 'id_member', 'id');
    }

    public function deposit_kelas(){
        return $this->hasMany(deposit_kelas::class, 'id_member', 'id');
    }

    public function booking_gym()
    {
        return $this->hasMany(BookingGym::class, 'id_member');
    }

}

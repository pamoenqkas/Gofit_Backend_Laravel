<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\BookingKelasResource;
use App\Http\Resources\LaporanPendapatanBulananResource;
use App\Models\AktivasiTahunan;
use App\Models\BookingKelas;
use App\Models\JadwalHarian;
use App\Models\Member;
use App\Models\PresensiGym;
use App\Models\DepositKelas;
use App\Models\DepositUmum;
use App\Models\PresensiKelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class AktivitasGymBulananController extends Controller
{
    public function store(){
        $laporan = collect([]);
        $tanggal_cetak = Carbon::now();
        $tanggal_cetak_angka= Carbon::now()->format('d');
        $bulan = Carbon::now()->format('F');    
        $tahun = Carbon::now()->format('Y');
        $bulan_angka = Carbon::now()->format('m');
        $tanggal = Carbon::now()->format('d F Y');
        $total_semua = 0;
        
        for($i = 1;$i<=$tanggal_cetak_angka;$i++){
            $jumlah_member = PresensiGym::where('tanggal_presensi_gym', $tahun.'-'.$bulan_angka.'-'.$i)->where('status', '=', 'Hadir')->get();
            $storeData['tanggal'] = $tahun.'-'.$bulan.'-'.$i;
            foreach($tanggal_cetak as $data){
                $storeData['tanggal'] = $data->tanggal_cetak;
            }
            $storeData['jumlah_member'] = 0;
            $storeData['jumlah_member'] = count($jumlah_member);
            $laporan->add($storeData);
        }       
        $storeData['total_semua'] = 0;
        foreach($laporan as $item){
            $total_semua = $item['jumlah_member'] + $total_semua;
        }

        return response([
            'data' => $laporan,
            'bulan' => $bulan,
            'tanggal' => $tanggal,
            'tahun' => $tahun,
            'total_semua' => $total_semua,
        ]);
    }
}

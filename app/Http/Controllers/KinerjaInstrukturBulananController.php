<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingKelasResource;
use App\Http\Resources\LaporanPendapatanBulananResource;
use App\Models\AktivasiTahunan;
use App\Models\BookingKelas;
use Illuminate\Http\Request;
use App\Models\JadwalHarian;
use App\Models\Member;
use App\Models\LaporanPendapatanBulanan;
use App\Models\DepositKelas;
use App\Models\Instruktur;
use App\Models\PresensiInstruktur;
use App\Models\PresensiKelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class KinerjaInstrukturBulananController extends Controller
{
    public function store(){
        
        //Ambil periode/ tahun 
        $tahun = Date::now()->year;
        $tanggal_cetak = Carbon::now()->format('d F Y');
        // Ambil bulan saat ini
        // $bulanIni = Carbon::now()->format('Y-m');
        $now = Carbon::now();
        $bulan = substr($now, 5,2);
        $bulan_cetak = Carbon::now()->format('F');

        $laporan = collect([]);

        $instruktur = Instruktur::all();
        // $jadwal_harian = JadwalHarian::all();
        $booking = BookingKelas::all();

            foreach($instruktur as $item1){
                $storeData['nama_instruktur'] = $item1->nama_instruktur;
                $storeData['jumlah_hadir'] = 0;
                $storeData['jumlah_libur'] = 0;
                $storeData['total_terlambat'] = 0;
                $storeData['total_terlambat'] = strtotime($item1->total_terlambat) - strtotime('00:00:00');
                $jadwal_harian = JadwalHarian::where('id_instruktur', $item1->id_instruktur)->where('keterangan', '=', 'Aktif')->get();
                foreach($jadwal_harian as $item2){
                    $jumlahHadir = PresensiInstruktur::where('id_jadwal_harian', $item2->id_jadwal_harian)->where('kehadiran' , '=', 'Sudah di presensi')->get();
                    $storeData['jumlah_hadir'] = $storeData['jumlah_hadir'] + count($jumlahHadir);
                    }
                    $jadwalLibur = JadwalHarian::where('id_instruktur', $item1->id_instruktur)->where('keterangan', '=', 'Libur')->get();
                    $storeData['jumlah_libur'] = $storeData['jumlah_libur'] + count($jadwalLibur);
                    // $terlambat = Instruktur::where('id_instruktur', $item1->id_instruktur)->where('total_terlambat', $item1->total_terlambat)->get();
                    // $storeData['total_terlambat'] = $terlambat['total_terlambat'];
                    $laporan->add($storeData);                   
                }
                
            // return response([
            //     'message' => 'Retrieve All Success',
            //     'data' => $jwh
            // ], 200);

        if(!is_null($laporan)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulan_cetak,
                'tahun' => $tahun,
                'tanggal_cetak' => $tanggal_cetak,
            ], 200);
        }
    }
}

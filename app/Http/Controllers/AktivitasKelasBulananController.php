<?php

namespace App\Http\Controllers;

use App\Http\Resources\AktivitasKelasBulananResource;
use App\Http\Resources\BookingKelasResource;
use App\Http\Resources\LaporanPendapatanBulananResource;
use App\Models\AktivasiTahunan;
use App\Models\AktivitasKelasBulanan;
use App\Models\BookingKelas;
use Illuminate\Http\Request;
use App\Models\JadwalHarian;
use App\Models\Member;
use App\Models\LaporanPendapatanBulanan;
use App\Models\DepositKelas;
use App\Models\Instruktur;
use App\Models\JadwalUmum;
use App\Models\PresensiKelas;
use App\Models\Kelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;


class AktivitasKelasBulananController extends Controller
{
    public function index()
    {
        $aktivitas_kelas_bulanan = AktivitasKelasBulanan::all();
        //render view with posts
        return new AktivitasKelasBulananResource(
            true,
            'List Data Kelas',
            $aktivitas_kelas_bulanan
        );
    }

    public function create()
    {
        return view('aktivitas_kelas_bulanan.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $aktivitas_kelas_bulanan = AktivitasKelasBulanan::all();
        $validator = Validator::make($request->all(), [
            'id_aktivitas_kelas_bulanan' => '',
            'bulan' => '',
            'tahun' => '',
            'tanggal_cetak' => '',
            'kelas' => '',
            'instruktur' => '',
            'jumlah_peserta' => '',
            'jumlah_libur' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }


        //Ambil periode/ tahun 
        $tahun = Date::now()->year;
        $tanggal_cetak = Carbon::now()->format('d F Y');
        // Ambil bulan saat ini
        // $bulanIni = Carbon::now()->format('Y-m');
        $now = Carbon::now();
        $bulan = substr($now, 5,2);
        $bulan_now = Carbon::now()->format('F');

        $laporan = collect([]);

        $kelas = Kelas::all();
        $instruktur = Instruktur::all();

        foreach($kelas as $item1){
            foreach($instruktur as $item2){
                $id1 = $item1->id_kelas;
                $id2 = $item2->id_instruktur;

                $jadwalHarian = JadwalHarian::whereMonth('tanggal', $bulan)->where('id_kelas','=',$id1)->where('id_instruktur','=',$id2)->get();                ;
                if(count($jadwalHarian)>0){
                    $storeData['kelas'] = $item1->nama_kelas;
                    $storeData['instruktur'] = $item2->nama_instruktur;

                    $jumlahLibur = JadwalHarian::whereMonth('tanggal', $bulan)->where('id_kelas','=', $item1->id_kelas)->
                        where('id_instruktur','=',$item2->id_instruktur)->where('keterangan','=','Libur')->get(); 
                    $storeData['jumlah_libur'] = count($jumlahLibur); 

                    $jumlahP = JadwalHarian::whereMonth('tanggal', $bulan)->where('id_kelas','=', $item1->id_kelas)->where('id_instruktur','=',$item2->id_instruktur)->get();

                    // $bookingK = BookingKelas::whereMonth('tanggal_booking_kelas', $bulan)->where('id_jadwal_harian', '=', $item4->id_jadwal_harian)->get();

                    $storeData['jumlah_peserta'] = 0;
                    foreach($jumlahP as $item3){
                        $jumlah_peserta = BookingKelas::where('id_jadwal_harian', '=', $item3->id_jadwal_harian)->get();
                        
                        foreach($jumlah_peserta as $item4){
                            $jumlahPeserta = PresensiKelas::where('id_booking_kelas','=',$item4->id_booking_kelas)->where('status','=','Hadir')->get();
                    
                        $storeData['jumlah_peserta'] = $storeData['jumlah_peserta'] + count($jumlahPeserta);
                        }
                        // $storeData['jumlah_peserta'] = $storeData['jumlah_peserta'] + count($jumlah_peserta);
                        $laporan->add($storeData);
                    }

                    // foreach($jumlahP as $item3){
                    //     $jumlahPeserta = BookingKelas::where('id_jadwal_harian','=',$item3->id_jadwal_harian)->where('status','=','Aktif')->get();
                    
                    //     $storeData['jumlah_peserta'] = $storeData['jumlah_peserta'] + count($jumlahPeserta);
                    // }
                                       
                }
                
            }
            // return response([
            //     'message' => 'Retrieve All Success',
            //     'data' => $jwh
            // ], 200);
        }

        if(!is_null($laporan)){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $laporan,
                'bulan' => $bulan_now,
                'tahun' => $tahun,
                'tanggal_cetak' => $tanggal_cetak,
            ], 200);
        }

        // $jadwalHarian = Jadwal_Harian::whereMonth('tanggal', $bulan)->get();
        // $booking = Booking_Kelas::all();
        // return response([
        //     'data' => $laporanAktivitas
        // ]);

        // return response([
        //     'tahun' => $tahun,
        //     'bulan' => $bulan,
        //     'tanggal_cetak' => $tanggal_cetak,
        //     'kelas' => $kelas,
        // ]);
        

      // Mengambil data dari tabel lain 
      // dan 
      // Mengubah menjadi dalam bentuk bulan
      $data = AktivasiTahunan::all();

      foreach ($data as $row) {
          // Mendapatkan nilai tanggal dari tabel lain
          $dateValue = $row->tanggal;

          // Mengubah nilai tanggal menjadi objek Carbon
          $carbonDate = Carbon::parse($dateValue);
  
          // Mengambil nama bulan dalam format teks
          $monthName = $carbonDate->format('F');
  
          // Mengganti nilai kolom tipe data tanggal dengan nama bulan
          $row->date_column = $monthName;
  
        
          $bulanDariTableLain = $row->date_column;

        //Bulan dari table lain udah dapat, di store ke table yang sama bulannya 
        //Bulan sekarang
        $currentMonth = Carbon::now()->format('F');
        
        // return response([
        //     // 'Total' => $total,
        //     'current month' => $currentMonth,
        //     'bulan talbe lain ' => $bulanDariTableLain
        // ]);

        // return response([
        //     // 'Total' => $total,
        //     'current month' => $currentMonth,
        //     'bulan talbe lain ' => $bulanDariTableLain
        // ]);


        if ($bulanDariTableLain == $currentMonth){
            // $datas = AktivasiTahunan::where($bulanDariTableLain, '=', $currentMonth)->get();
            // $matchingData = $dataTable2->whereMonth('month_column', $monthTable1);  

            $aktivasi_tahunan = AktivasiTahunan::latest()->get();

            foreach($aktivasi_tahunan as $data){
                $total = $data->sum('aktivasi_tahunan');
            }

            // $total = $aktivasi_tahunan->sum('aktivasi_tahunan');
            return response([
                'Total' => $total,
                'current month' => $currentMonth,
                'bulan talbe lain ' => $bulanDariTableLain
            ]);
        }

          // Simpan perubahan ke tabel lain
        //   $row->save();

        // $currentMonth = Carbon::now()->month;

        // // Iterasi melalui bulan-bulan dari Januari hingga Desember
        // for ($month = 1; $month <= 12; $month++) {
        //     // Jika bulan saat ini sesuai dengan bulan iterasi, simpan data
        //     if ($month === $currentMonth) {
        //         // Mendapatkan data dari tabel lain yang memiliki atribut bulan yang sama
        //         $datas = AktivasiTahunan::where($monthName, $month)->get();
        //         // Simpan data pada bulan yang sesuai

        //         // Lakukan operasi penyimpanan data sesuai kebutuhan Anda
        //         // Contoh: $model->field = $data;
    
        //         // Misalnya:
        //         foreach ($datas as $row) {
        //             // Simpan data pada bulan yang sesuai
        //             $total = $datas->sum('aktivasi_tahunan');
        //             // $row->save();
        //         }
        //     }
        // }
        // return response([
        //     'data' => $total,
        //     'data' => $total,
        // ]);
      }




    // $laporan_pendapatan_bulanan = LaporanPendapatanBulanan::latest()->first();
    // return new LaporanPendapatanBulananResource(true, 'Data Laporan Pendapatan Bulanan Ditambahkan!', $laporan_pendapatan_bulanan);
    }

}

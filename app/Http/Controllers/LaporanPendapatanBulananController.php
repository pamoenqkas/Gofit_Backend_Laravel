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
use App\Models\DepositUmum;
use App\Models\PresensiKelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;


class LaporanPendapatanBulananController extends Controller
{
    public function index()
    {
        $laporan_pendapatan_bulanan = LaporanPendapatanBulanan::all();
        //render view with posts
        return new LaporanPendapatanBulananResource(
            true,
            'List Data Kelas',
            $laporan_pendapatan_bulanan
        );
    }

    public function create()
    {
        return view('laporan_pendapatan_bulanan.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $laporan_pendapatan_bulanan = LaporanPendapatanBulanan::all();
        $validator = Validator::make($request->all(), [
            'id_laporan_pendapatan_bulanan' => '',
            'periode' => '',
            'tanggal_cetak' => '',
            'bulan' => '',
            'aktivasi' => '',
            'deposit' => '',
            'total' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $tanggal_cetak = Carbon::now()->toDateString();

       //Fungsi Post ke Database
    //    $laporan_pendapatan_bulanan = LaporanPendapatanBulanan::create([
    //     'id_laporan_pendapatan_bulanan' => $request->id_laporan_pendapatan_bulanan,
    //     'periode' => $request->periode,
    //     'tanggal_cetak' => $tanggal_cetak,
    //     'bulan' => $request->bulan,
    //     'aktivasi' => $request->aktivais,
    //     'deposit' => $request->deposit,
    //     'total' => $request->total,

    // ]);

    //Ambil periode/ tahun 
    $tahun = Date::now()->year;
    $tanggal_cetak = Carbon::now()->toDateString();
    

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

        $now = Carbon::now()->toDateString();
        $total_semua = 0;
        $laporan = collect([]);
        $tahun = Carbon::now()->format('Y');
        $tanggal_cetak = Carbon::now()->format('d F Y');
        for($i = 1;$i<13;$i++){
            // $carbonDate = Carbon::parse($i);
            // $monthName = $carbonDate->format('F');
            $bulan = Carbon::create(null, $i, 1)->format('F');
            $aktivasi = AktivasiTahunan::whereMonth('tanggal',$i)->get();
            $deposit_uang = DepositUmum::whereMonth('tanggal', $i)->get();
            $deposit_kelas = DepositKelas::whereMonth('tanggal',$i)->get();
            $storeData['bulan'] = $bulan;
            $storeData['total_aktivasi_deposit'] = 0;
            $storeData['deposit_uang'] = 0;
            foreach($deposit_uang as $data){
                $storeData['deposit_uang'] = $storeData['deposit_uang'] + $data->total_deposit;
            }
            $storeData['deposit_kelas'] = 0;
            foreach($deposit_kelas as $datas){
                $storeData['deposit_kelas'] = $storeData['deposit_kelas'] + $datas->total_deposit;
            }
            $storeData['aktivasi_tahunan'] = count($aktivasi) * 3000000;        
            $storeData['total'] = $storeData['deposit_uang'] + $storeData['deposit_kelas'];
            $storeData['total_aktivasi_deposit'] = $storeData['deposit_uang'] + $storeData['deposit_kelas'] + $storeData['aktivasi_tahunan'] + $storeData['total_aktivasi_deposit'];
            $laporan->add($storeData);
        }        
        $storeData['total_semua'] = 0;
        foreach($laporan as $item){
            $total_semua = $item['total_aktivasi_deposit'] + $total_semua;
        }

        return response([
            'data' => $laporan,
            'total_semua' => $total_semua,
            'tanggal_cetak' => $tanggal_cetak,
            'tahun' => $tahun
        ]);


        //3 jam ga ngapa ngapain == belajar
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
      


    // $laporan_pendapatan_bulanan = LaporanPendapatanBulanan::latest()->first();
    // return new LaporanPendapatanBulananResource(true, 'Data Laporan Pendapatan Bulanan Ditambahkan!', $laporan_pendapatan_bulanan);
    }

    public function edit($id_laporan_pendapatan_bulanan)
    {
        $laporan_pendapatan_bulanan = LaporanPendapatanBulanan::findOrFail($id_laporan_pendapatan_bulanan);
        return view('laporan_pendapatan_bulan.edit', compact('laporan_pendapatan_bulanan'));
    }

    // public function show($id_izin)
    // {
    //     $izin = Izin::find($id_izin);
    //     $jadwal_harian = JadwalHarian::all();
    //     $instruktur = Instruktur::all();

    //     if(!is_null($izin)){
    //         return new IzinResource(true, 'Data Ditemukan', $izin);
    //     }
    //     return new IzinResource(true, 'Data Tidak Ditemukan', $izin);
    // }

    // public function update(Request $request, $id_izin)
    // {
    //     $izin = Izin::find($id_izin); 

    //     if(is_null($izin)){
    //         return response([
    //             'message' => 'Izin Instruktur Not Found',
    //             'data' => null
    //         ], 404);
    //     } 

    //     $updateData = $request->all();
    //     $validate = Validator::make($updateData, [
    //         'status' => 'required',
    //     ]);
    //     if($validate->fails()){
    //         return response(['message' => $validate->errors()], 400); 
    //     }

    //     $izin->status = $updateData['status'];
    //     $jadwal_harian = $request->id_jadwal_harian;
    //     $findJadwalHarian = JadwalHarian::find($jadwal_harian);

    //     $dekskripsiIzinInput = $request->input('deskripsi_izin');
    //     $findJadwalHarian->keterangan = $dekskripsiIzinInput;

    //     $namaPenggantiInput = $request->input('id_instruktur_pengganti');
    //     $findJadwalHarian->id_instruktur = $namaPenggantiInput;
    //     $findJadwalHarian->save();

    //     if($izin->save()){
    //         return response([
    //             'message' => 'Update Izin Success',
    //             'data' => $izin
    //         ], 200);
    //     }

    //     return response([
    //         'message' => 'Update Izin Failed',
    //         'data' => null
    //     ], 400);
    // }        
    
    // public function destroy($id_izin)
    // {
    //     $izin = JadwalHarian::find($id_izin); 

    //     if(is_null($izin)){
    //         return response([
    //             'message' => 'Izin Not Found',
    //             'date' => null
    //         ], 404);
    //     } 

    //     if($izin->delete()){
    //         $izin->delete();
    //         return response([
    //             'message' => 'Delete Izin Instruktur Success',
    //             'data' => $izin
    //         ], 200);
    //     } 
    //     return response([
    //         'message' => 'Delete Izin Instruktur Failed',
    //         'data' => null, 
    //     ],400);
    // }
}

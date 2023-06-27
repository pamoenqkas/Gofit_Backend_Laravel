<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\JadwalHarianResource;
use App\Http\Resources\PresensiResource;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalHarian;
use App\Models\PresensiInstruktur;
use App\Models\Kelas;
use App\Models\Instruktur;
Use Illuminate\Support\Carbon;


class PresensiInstrukturController extends Controller
{
    public function index()
    {
        $presensi_instruktur = PresensiInstruktur::with('jadwal_harian')->latest()->get();
        $jadwal_harian = JadwalHarian::latest()->get();

        //render view with posts
        return new JadwalHarianResource(
            true,
            'List Data Presensi Instruktur',
            $presensi_instruktur
        );
    }

    public function create()
    {
        return view('presensi_instruktur.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_presensi_instruktur' => '',
            'id_jadwal_harian' => '',
            'jam_mulai' => '',
            'jam_selesai' => '',
            'keterlambatan' => '',
            'kehadiran' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $checkJadwal = JadwalHarian::where('hari', $request['hari'])
        //                             ->where('jam_mulai', $request['jam_mulai'])
        //                             ->where('id_instruktur', $request['id_instruktur'])
        //                             ->get();
        
        // if(count($checkJadwal) != 0){
        //     return new JadwalHarianResource(true, 'Jadwal Bertabrakan!', $jadwal_harian);    
        // }

        $jadwal_harian = JadwalHarian::all();
        foreach($jadwal_harian as $item){
            $storeData['id_jadwal_harian'] = $item->id_jadwal_harian;
            $storeData['jam_mulai'] = $item->jam_mulai;
            $storeData['jam_selesai'] = $item->jam_selesai;
            $storeData['keterlambatan'] = '00:00:00';
            $storeData['kehadiran'] = 'Belum Di Presensi';

            $id_temp = $item->id_jadwal_harian;
            $id = substr($id_temp,-2);
            
            $storeData['id_presensi_instruktur'] = 'PI'.$id;
            

            $presensi_instruktur = PresensiInstruktur::create($storeData);
            $presensi_instruktur = PresensiInstruktur::latest()->first();              
        }
        $presensi_instruktur = PresensiInstruktur::all();
        return new PresensiResource(true, 'Data Presensi Instruktur Berhasil Ditambahkan!', $presensi_instruktur);    
    }

    public function edit($id_presensi_instruktur)
    {
        $presensi_instruktur = JadwalHarian::findOrFail($id_presensi_instruktur);
        return view('presensi_instruktur.edit', compact('presensi_instruktur'));
    }

    public function show($id_presensi_instruktur)
    {
        $presensi_instruktur = PresensiInstruktur::find($id_presensi_instruktur);
        $jadwal_harian = JadwalHarian::all();

        if(!is_null($presensi_instruktur)){
            return new PresensiResource(true, 'Data Ditemukan', $presensi_instruktur);
        }
        return new PresensiResource(true, 'Data Tidak Ditemukan', $presensi_instruktur);
    }

    public function update($id_presensi_instruktur)
    {
        $presensi_instruktur = PresensiInstruktur::find($id_presensi_instruktur); 

        if(is_null($presensi_instruktur)){
            return response([
                'message' => 'Presensi Instruktur Not Found',
                'data' => null
            ], 404);
        } 

        if ($presensi_instruktur->kehadiran == 'Belum Di Presensi') {

            $jadwalHarian = JadwalHarian::findOrFail($presensi_instruktur->id_jadwal_harian)->first();
            $kelas = Kelas::findOrFail($jadwalHarian->id_kelas)->first();

            $hargaKelas = $kelas->harga;

            return response([
                'data' => $hargaKelas
            ]);

            // Ubah nilai status menjadi "Sudah di presensi"
            $presensi_instruktur->kehadiran = "Sudah di presensi";
            $presensi_instruktur->jam_mulai = Carbon::now()->toTimeString();
            $presensi_instruktur->keterlambatan = '08:00:00';
            if($presensi_instruktur->save()){
                return response([
                    'message' => 'Update Presensi Instruktur Success',
                    'data' => $presensi_instruktur
                ], 200);
            }
            return response([
                'message' => 'Presensi Instruktur Berhasil di Presensi',
            ],400);
        } else {
            // Tambahkan pesan jika data tidak ditemukan
            return response([
                'message' => 'Presensi Instruktur Gagal di Presensi',
                'data' => null, 
            ],400);
        }

        return response([
            'message' => 'Update instruktur Failed',
            'data' => null
        ], 400);
    }        
    
    public function destroy($id_presensi_instruktur)
    {
        $presensi_instruktur = JadwalHarian::find($id_presensi_instruktur); 

        if(is_null($presensi_instruktur)){
            return response([
                'message' => 'Presensi Instruktur Not Found',
                'date' => null
            ], 404);
        } 

        if($presensi_instruktur->delete()){
            $presensi_instruktur->delete();
            return response([
                'message' => 'Delete Presensi Instruktur Success',
                'data' => $presensi_instruktur
            ], 200);
        } 
        return response([
            'message' => 'Delete Presensi Instruktur Failed',
            'data' => null, 
        ],400);
    }
}

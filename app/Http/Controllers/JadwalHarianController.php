<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\JadwalHarianResource;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalHarian;
use App\Models\JadwalUmum;
use App\Models\Kelas;
use App\Models\Instruktur;
Use Illuminate\Support\Carbon;
use Illuminate\Support\Str;



class JadwalHarianController extends Controller
{
    public function index()
    {
        $jadwal_harian = JadwalHarian::with('kelas', 'instruktur')->latest()->get();
        $kelas = Kelas::latest()->get();
        $instruktur = Instruktur::latest()->get();
        $currentDate = Carbon::now();
        $startDate = $currentDate->startOfWeek()->toDateString(); // Tanggal awal minggu
        $endDate = $currentDate->endOfWeek()->toDateString();     // Tanggal akhir minggu
        
        $jadwalHarian = JadwalHarian::whereBetween('tanggal', [$startDate, $endDate])->with('kelas', 'instruktur')->get();

        // if ($jadwalHarian->count() >= 0) {
        //     return response([
        //         'data' => $jadwalHarian->count(),
        //         'message' => 'Sudah melakukan generate untuk minggu depan'
        //     ]);
        // }
        
        return response([
            'data' => $jadwalHarian
        ]);

        //render view with posts
        // return new JadwalHarianResource(
        //     true,
        //     'List Data Jadwal Harian',
        //     $jadwalHarian
        // );
    }

    public function create()
    {
        return view('jadwal_harian.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $jadwal_harian = JadwalHarian::all();
        $validator = Validator::make($request->all(), [
            'id_jadwal_harian' => '',
            'id_kelas' => '',
            'id_instruktur' => '',
            'hari' => '',
            'jam_mulai' => '',
            'jam_selesai' => '',
            'tanggal' => '',
            'keterangan' => '',
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

        // Periksa apakah sudah ada jadwal harian pada minggu pertama

        // return response([
        //     'data' => $jadwalHarianMingguPertama
        // ]);


        $jadwal_umum = JadwalUmum::all();

        $last = JadwalHarian::latest('id_jadwal_harian')->first();
        //kondisi udah ada data
        // return response([
        //     'data' => $last
        // ]);
        
            // return response([
            //     'tanggalStart' => $startDate,
            //     'tanggalEnd' => $endDate,
            // ]);

                // if pertama -> jadwal harian kosong -> tambah data 
                // if kedua -> jadwal ada -> ga tambah data 
                // if ketiga -> jadwal 

        // meriksa ada data di antara awal minggu dan akhir minggu
        // return response([
        //     'data' => $jumlahJadwal
        // ]);

        $jumlahJadwal = JadwalHarian::count();
        if ($jumlahJadwal > 0){  
            $currentDate = Carbon::now();
            $startDate = $currentDate->startOfWeek()->toDateString(); // Tanggal awal minggu
            $endDate = $currentDate->endOfWeek()->toDateString();     // Tanggal akhir minggu
            $jadwalHarian = JadwalHarian::whereBetween('tanggal', [$startDate, $endDate])->get();
            if ($jadwalHarian->count() == 0) {
                return response([
                    'data' => $jadwalHarian->count(),
                    'message' => 'Sudah melakukan generate untuk minggu depan'

                ]);
            } else if ($jadwalHarian->count() > 0) {
                // Tidak ada jadwal harian untuk minggu ini
                                                             // lakukan generate untuk minggu depan (tidak pertama kali)
                    return response([
                        'dassta' => $jadwalHarian
                    ]);
                    foreach($jadwal_umum as $item){
                    $last = JadwalHarian::latest('id_jadwal_harian')->first();
                    $idJadwalHarian = ((int)Str::substr($last->id_jadwal_harian, 2,3)) + 1;
                    // return response([
                    //     'data' => $idJadwalHarian
                    // ]);
                    // $last = ((int)Str::substr($last->id_jadwal_harian, 3,2));
    
                
                    // return response([
                    //     'data' => $idJadwalHarian
                    // ]);
                    if($idJadwalHarian < 10){
                        $idJadwalHarian = '00'.$idJadwalHarian;
                    }else if($idJadwalHarian < 100){
                        $idJadwalHarian = '0'.$idJadwalHarian;
                    }else if ($idJadwalHarian < 1000){
                        $idJadwalHarian = ''.$idJadwalHarian;
                    }

                    // return response([
                    //     'data' => $idJadwalHarian
                    // ]);
                    $storeData['id_jadwal_harian'] = 'JH'.$idJadwalHarian;
                    $storeData['id_kelas'] = $item->id_kelas;
                    $storeData['id_instruktur'] = $item->id_instruktur;
                    $storeData['hari'] = $item->hari;
                    $storeData['jam_mulai'] = $item->jam_mulai;
                    $storeData['jam_selesai'] = $item->jam_selesai;
                    $storeData['keterangan'] = 'Aktif';
    
                    $now = Carbon::now();
                    $temp_tgl = $now->addDays(7); 
                    $weekStartDate = $temp_tgl->startOfWeek();
    
                    if($item->hari == 'Senin'){
                        $storeData['tanggal'] = $weekStartDate;
                    }elseif($item->hari == 'Selasa'){
                        $storeData['tanggal'] = $weekStartDate->addDays(1);
                    }elseif($item->hari == 'Rabu'){
                        $storeData['tanggal'] = $weekStartDate->addDays(2);
                    }elseif($item->hari == 'Kamis'){
                        $storeData['tanggal'] = $weekStartDate->addDays(3);
                    }elseif($item->hari == 'Jumat'){
                        $storeData['tanggal'] = $weekStartDate->addDays(4);
                    }elseif($item->hari == 'Sabtu'){
                        $storeData['tanggal'] = $weekStartDate->addDays(5);
                    }elseif($item->hari == 'Minggu'){
                        $storeData['tanggal'] = $weekStartDate->addDays(6);
                    }
                    $jadwal_harian = JadwalHarian::create($storeData);   
                    $jadwal_harian = JadwalHarian::latest()->first();    
                    // return new JadwalHarianResource(true, 'Data Jadwal Harian Berhasil Ditambahkan!', $jadwal_harian);  
                    // return response([
                    //     'data' => $idJadwalHarian,
                    // ]);
                }
            }else{
            }
            $jadwal_harian = JadwalHarian::all();
            return new JadwalHarianResource(true, 'Data Jadwal Harian Berhasil Ditambahkansss!', $jadwal_harian);    
        //kondisi belum ada data 
        }
                                                                //pertama kali generate
            foreach($jadwal_umum as $item){
                $last = JadwalHarian::latest()->first();
                // $id_jadwal_harian = 'JH'.$id_jadwal_harian;
                $storeData['id_kelas'] = $item->id_kelas;
                $storeData['id_instruktur'] = $item->id_instruktur;
                $storeData['hari'] = $item->hari;
                $storeData['jam_mulai'] = $item->jam_mulai;
                $storeData['jam_selesai'] = $item->jam_selesai;
                $storeData['keterangan'] = 'Aktif';
    
                $id_temp = $item->id_jadwal_umum;
                $id = substr($id_temp,-3);
    
                $storeData['id_jadwal_harian'] = 'JH'.$id;
    
    
                $now = Carbon::now();
                $temp_tgl = $now->addDays(7); 
                $weekStartDate = $temp_tgl->startOfWeek();
    
                if($item->hari == 'Senin'){
                    $storeData['tanggal'] = $weekStartDate;
                }elseif($item->hari == 'Selasa'){
                    $storeData['tanggal'] = $weekStartDate->addDays(1);
                }elseif($item->hari == 'Rabu'){
                    $storeData['tanggal'] = $weekStartDate->addDays(2);
                }elseif($item->hari == 'Kamis'){
                    $storeData['tanggal'] = $weekStartDate->addDays(3);
                }elseif($item->hari == 'Jumat'){
                    $storeData['tanggal'] = $weekStartDate->addDays(4);
                }elseif($item->hari == 'Sabtu'){
                    $storeData['tanggal'] = $weekStartDate->addDays(5);
                }elseif($item->hari == 'Minggu'){
                    $storeData['tanggal'] = $weekStartDate->addDays(6);
                }
    
                $jadwal_harian = JadwalHarian::create($storeData);
                $jadwal_harian = JadwalHarian::latest()->first();    
            }
            $jadwal_harian = JadwalHarian::all();
            return new JadwalHarianResource(true, 'Data Jadwal Harian Berhasil Ditambahkan 1!', $jadwal_harian);  
    }

    public function edit($id_jadwal_harian)
    {
        $jadwal_harian = JadwalHarian::findOrFail($id_jadwal_harian);
        return view('jadwal_harian.edit', compact('jadwal_harian'));
    }

    public function show($id_jadwal_harian)
    {
        $jadwal_harian = JadwalHarian::find($id_jadwal_harian);
        $kelas = Kelas::all();
        $instruktur = Instruktur::all();

        if(!is_null($jadwal_harian)){
            return response([
                'data' => $jadwal_harian,
                'nama_kelas' => $kelas->nama_kelas,
                'nama_instruktur' => $instruktur->nama_instruktur,
            ]);
            // return new JadwalHarianResource(true, 'Data Ditemukan', $jadwal_harian);
        }
        return new JadwalHarianResource(true, 'Data Tidak Ditemukan', $jadwal_harian);
    }

    public function update(Request $request, $id_jadwal_harian)
    {
        $jadwal_harian = JadwalHarian::find($id_jadwal_harian); 

        if(is_null($jadwal_harian)){
            return response([
                'message' => 'jadwal Harian Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'keterangan' => 'required',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $jadwal_harian->keterangan = $updateData['keterangan'];

        if($jadwal_harian->save()){
            return response([
                'message' => 'Update instruktur Success',
                'data' => $jadwal_harian
            ], 200);
        }

        return response([
            'message' => 'Update instruktur Failed',
            'data' => null
        ], 400);
    }        
    
    public function destroy($id_jadwal_harian)
    {
        $jadwal_harian = JadwalHarian::find($id_jadwal_harian); 

        if(is_null($jadwal_harian)){
            return response([
                'message' => 'Jadwal Harian Not Found',
                'date' => null
            ], 404);
        } 

        if($jadwal_harian->delete()){
            $jadwal_harian->delete();
            return response([
                'message' => 'Delete Jadwal Harian Success',
                'data' => $jadwal_harian
            ], 200);
        } 
        return response([
            'message' => 'Delete Jadwal Harian Failed',
            'data' => null, 
        ],400);
    }
}

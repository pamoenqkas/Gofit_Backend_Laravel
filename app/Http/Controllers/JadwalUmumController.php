<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\JadwalUmumResource;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalUmum;
use App\Models\Kelas;
use App\Models\Instruktur;
use Illuminate\Support\Str;
use Carbon\Carbon;


class JadwalUmumController extends Controller
{
    public function index()
    {
        $jadwal_umum = JadwalUmum::with('kelas', 'instruktur')->latest()->get();
        $kelas = Kelas::latest()->get();
        $instruktur = Instruktur::latest()->get();
        //render view with posts
        return new JadwalUmumResource(
            true,
            'List Data Jadwal Umum',
            $jadwal_umum
        );
    }

    public function create()
    {
        return view('jadwal_umum.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $jadwal_umum = JadwalUmum::all();
        $validator = Validator::make($request->all(), [
            'id_jadwal_umum' => '',
            'id_kelas' => 'required',
            'id_instruktur' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

    //     if(($jadwal_umum->id_instruktur == $request->id_instructor) && 
    //            ($jadwal_umum->start_time == $request->start_time) &&
    //            ($jadwal_umum->date == $request->date)){
    //             return new JadwalUmumResource(true, 'Jadwal Bertabrakan!', $jadwal_umum);    
    //    }

        $checkJadwal = JadwalUmum::where('hari', $request['hari'])
                                    ->where('jam_mulai', $request['jam_mulai'])
                                    ->where('id_instruktur', $request['id_instruktur'])
                                    ->get();
        
        if(count($checkJadwal) != 0){
            return new JadwalUmumResource(true, 'Jadwal Bertabrakan!', $jadwal_umum);    
        }
        $last = JadwalUmum::latest()->first();
        if($last == null){
            $id_jadwal_umum = 1;
        }else{
            $id_jadwal_umum = ((int)Str::substr($last->id_jadwal_umum, 2,3)) + 1;
        }
        if($id_jadwal_umum < 10){
            $id_jadwal_umum = '00'.$id_jadwal_umum;
        }else if($id_jadwal_umum <100){
            $id_jadwal_umum = '0'.$id_jadwal_umum;
        }

        $jadwal_umum = JadwalUmum::create([
            'id_jadwal_umum' => 'JU'.$id_jadwal_umum,
            'id_kelas' => $request->id_kelas,
            'id_instruktur' => $request->id_instruktur,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);
        $jadwal_umum = JadwalUmum::latest()->first();
        return new JadwalUmumResource(true, 'Data Jadwal Umum Berhasil Ditambahkan!', $jadwal_umum);    
    }

    public function edit($id_jadwal_umum)
    {
        $jadwal_umum = JadwalUmum::findOrFail($id_jadwal_umum);
        return view('jadwal_umum.edit', compact('jadwal_umum'));
    }

    public function show($id_jadwal_umum)
    {
        $jadwal_umum = JadwalUmum::find($id_jadwal_umum);

        if(!is_null($jadwal_umum)){
            return new JadwalUmumResource(true, 'Data Ditemukan', $jadwal_umum);
        }
        return new JadwalUmumResource(true, 'Data Tidak Ditemukan', $jadwal_umum);
    }

    public function update(Request $request, $id_jadwal_umum)
    {
        $jadwal_umum = JadwalUmum::find($id_jadwal_umum); 

        if(is_null($jadwal_umum)){
            return response([
                'message' => 'jadwal umum Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_kelas' => 'required',
            'id_instruktur' => 'required',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        
        $checkJadwal = JadwalUmum::where('hari', $request['hari'])
                                    ->where('jam_mulai', $request['jam_mulai'])
                                    ->where('id_instruktur', $request['id_instruktur'])
                                    ->get();
        
        if(count($checkJadwal) != 0){
            return new JadwalUmumResource(true, 'Jadwal Bertabrakan!', $jadwal_umum);    
        }
        
        $jadwal_umum->id_kelas = $updateData['id_kelas'];
        $jadwal_umum->id_instruktur = $updateData['id_instruktur'];
        $jadwal_umum->hari = $updateData['hari'];
        $jadwal_umum->jam_mulai = $updateData['jam_mulai'];
        $jadwal_umum->jam_selesai = $updateData['jam_selesai'];

        if($jadwal_umum->save()){
            return new JadwalUmumResource(true, 'Data Jadwal Umum Berhasil Diupdate', $jadwal_umum);
        }
        return new JadwalUmumResource(true, 'Data Jadwal Umum Gagal Diupdate', $jadwal_umum);
    }        
    
    public function destroy($id_jadwal_umum)
    {
        $jadwal_umum = JadwalUmum::find($id_jadwal_umum); 

        if(is_null($jadwal_umum)){
            return response([
                'message' => 'Jadwal Umum Not Found',
                'date' => null
            ], 404);
        } 

        if($jadwal_umum->delete()){
            $jadwal_umum->delete();
            return response([
                'message' => 'Delete Jadwal Umum Success',
                'data' => $jadwal_umum
            ], 200);
        } 
        return response([
            'message' => 'Delete Jadwal Umum Failed',
            'data' => null, 
        ],400);
    }
}

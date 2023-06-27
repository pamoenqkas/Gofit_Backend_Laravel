<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\IzinResource;
use Illuminate\Support\Facades\Validator;
use App\Models\JadwalHarian;
use App\Models\Izin;
use App\Models\Instruktur;
Use Illuminate\Support\Carbon;
use illuminate\support\Str;

class IzinController extends Controller
{
    public function index()
    {
        $izin = Izin::with('jadwal_harian', 'instruktur')->latest()->get();
        $jadwal_harian = JadwalHarian::latest()->get();
        $instruktur = Instruktur::latest()->get();

        //render view with posts
        return new IzinResource(
            true,
            'List Data Izin Instruktur',
            $izin
        );
    }

    public function create()
    {
        return view('izin.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $izin = Izin::all();
        $validator = Validator::make($request->all(), [
            'id_izin' => '',
            'id_instruktur' => 'required',
            'id_jadwal_harian' => 'required',
            'id_instruktur_pengganti' => 'required',
            'tanggal' => '',
            'deskripsi_izin' => 'required',
            'status' => '',
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

        $dekskripsiIzinInput = $request->input('deskripsi_izin');

        $tanggal = Carbon::now()->toDateString();

        $last = Izin::latest()->first();
        if($last == null){
            $id_izin = 1;
        }else{
            $id_izin = ((int)Str::substr($last->id_izin, 2,3)) + 1;
        }

        if($id_izin < 10){
            $id_izin = '00'.$id_izin;
        }else if($id_izin < 100){
            $id_izin = '0'.$id_izin;
        }

       //Fungsi Post ke Database
       $izin = Izin::create([
        'id_izin' => 'IZ'.$id_izin,
        'id_instruktur' => $request->id_instruktur,
        'id_jadwal_harian' => $request->id_jadwal_harian,
        'id_instruktur_pengganti' => $request->id_instruktur_pengganti,
        'tanggal' => $tanggal,
        'deskripsi_izin' => $dekskripsiIzinInput,
        'status' => 'Belum di konfirmasi'
    ]);

    $izin = Izin::latest()->first();
    return new IzinResource(true, 'Data Izin Instruktur Berhasil Ditambahkan!', $izin);
    }

    public function edit($id_izin)
    {
        $izin = JadwalHarian::findOrFail($id_izin);
        return view('izin.edit', compact('izin'));
    }

    public function show($id_izin)
    {
        $izin = Izin::find($id_izin);
        $jadwal_harian = JadwalHarian::all();
        $instruktur = Instruktur::all();

        if(!is_null($izin)){
            return new IzinResource(true, 'Data Ditemukan', $izin);
        }
        return new IzinResource(true, 'Data Tidak Ditemukan', $izin);
    }

    public function update(Request $request, $id_izin)
    {
        $izin = Izin::find($id_izin); 

        if(is_null($izin)){
            return response([
                'message' => 'Izin Instruktur Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'status' => '',
        ]);
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $izin->status = 'Dikonfirmasi';
        $jadwal_harian = $request->id_jadwal_harian;
        $findJadwalHarian = JadwalHarian::find($jadwal_harian);

        // $dekskripsiIzinInput = $request->input('deskripsi_izin');
        $findJadwalHarian->keterangan = 'Libur';

        $namaPenggantiInput = $request->input('id_instruktur_pengganti');
        $findJadwalHarian->id_instruktur = $namaPenggantiInput;
        $findJadwalHarian->save();

        if($izin->save()){
            return response([
                'message' => 'Update Izin Success',
                'data' => $izin
            ], 200);
        }

        return response([
            'message' => 'Update Izin Failed',
            'data' => null
        ], 400);
    }        
    
    public function destroy($id_izin)
    {
        $izin = JadwalHarian::find($id_izin); 

        if(is_null($izin)){
            return response([
                'message' => 'Izin Not Found',
                'date' => null
            ], 404);
        } 

        if($izin->delete()){
            $izin->delete();
            return response([
                'message' => 'Delete Izin Instruktur Success',
                'data' => $izin
            ], 200);
        } 
        return response([
            'message' => 'Delete Izin Instruktur Failed',
            'data' => null, 
        ],400);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instruktur;
use App\Http\Resources\InstrukturResource;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class InstrukturController extends Controller
{
    public function index()
    {
        $instruktur = Instruktur::all();
        //render view with posts
        return new InstrukturResource(
            true,
            'List Data Instruktur',
            $instruktur
        );
    }

    public function create()
    {
        return view('instruktur.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_instruktur' => 'required',
            'nama_instruktur' => 'required',
            'no_telp_instruktur' => 'required',
            'alamat_instruktur' => 'required',
            'email_instruktur' => 'required',
            'tanggal_lahir_instruktur' => 'required',
            'password' => 'required',
            'total_terlambat' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $instruktur = Instruktur::create([
            'id_instruktur' => $request->id_instruktur,
            'nama_instruktur' => $request->nama_instruktur,
            'no_telp_instruktur' => $request->no_telp_instruktur,
            'alamat_instruktur' => $request->alamat_instruktur,
            'email_instruktur' => $request->email_instruktur,
            'tanggal_lahir_instruktur' => $request->tanggal_lahir_instruktur,
            'password' => $request->password,
            'total_terlambat' => $request->total_terlambat,

        ]);
        $instruktur = Instruktur::latest()->first();
        return new InstrukturResource(true, 'Data Instruktur Berhasil Ditambahkan!', $instruktur);
    }

    public function edit($id)
    {
        $instruktur = Instruktur::findOrFail($id);
        return view('instruktur.edit', compact('instruktur'));
    }
    
    public function show($id_instruktur)
    {
        $instruktur = Instruktur::find($id_instruktur);

        if(!is_null($instruktur)){
            return response([
                'data' => $instruktur,
                'nama_instruktur' => $instruktur->nama_instruktur,
            ]);
            // return new InstrukturResource(true, 'Data Ditemukan', $instruktur);
        }
        return new InstrukturResource(true, 'Data Tidak Ditemukan', $instruktur);
    }


//     public function update(Request $request, $id_instruktur)
//     {
//         $instruktur = Instruktur::find($id_instruktur);
//         if (is_null($instruktur)) {
//             return response([
//                 'message' => 'Instruktur Not Found',
//                 'data' => null
//             ], 404);
//         }
//         $this->validate($request, [
//             'nama_instruktur' => 'required',
//             'no_telp_instruktur' => 'required',
//             'alamat_instruktur' => 'required',
//             'email_instruktur' => 'required',
//             'tanggal_lahir_instruktur' => 'required',
//             'password' => 'required',
//         ]);

//         $instruktur->update($request->all())
// ([
//             // 'id_instruktur' => $id_instruktur,
//             'nama_instruktur' => $request->nama_instruktur,
//             'no_telp_instruktur' => $request->no_telp_instruktur,
//             'alamat_instruktur' => $request->alamat_instruktur,
//             'email_instruktur' => $request->email_instruktur,
//             'tanggal_lahir_instruktur' => $request->tanggal_lahir_instruktur,
//             'password' => $request->password,
//         ]);

//         if($instruktur){
//             return new InstrukturResource(true, 'Data Berhasil Edit', $instruktur); 
//         }else{
//             return new InstrukturResource(true, 'Data Gagal Edit', $instruktur); 
//         }
//     }

    public function update(Request $request, $id_instruktur)
    {
        $instruktur = Instruktur::find($id_instruktur); 

        if(is_null($instruktur)){
            return response([
                'message' => 'instruktur Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_instruktur' => 'required',
            'no_telp_instruktur' => 'required',
            'alamat_instruktur' => 'required',
            'email_instruktur' => 'required',
            'tanggal_lahir_instruktur' => 'required',
            'password' => '',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $instruktur->nama_instruktur = $updateData['nama_instruktur'];
        $instruktur->no_telp_instruktur = $updateData['no_telp_instruktur'];
        $instruktur->alamat_instruktur = $updateData['alamat_instruktur'];
        $instruktur->email_instruktur = $updateData['email_instruktur'];
        $instruktur->tanggal_lahir_instruktur = $updateData['tanggal_lahir_instruktur'];

        if($instruktur->save()){
            return response([
                'message' => 'Update instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Update instruktur Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_instruktur)
    {
        $instruktur = Instruktur::find($id_instruktur); 

        if(is_null($instruktur)){
            return response([
                'message' => 'Instruktur Not Found',
                'date' => null
            ], 404);
        } 

        if($instruktur->delete()){
            $instruktur->delete();
            return response([
                'message' => 'Delete Instruktur Success',
                'data' => $instruktur
            ], 200);
        }

        return response([
            'message' => 'Delete Instruktur Failed',
            'data' => null, 
        ],400);
    }

    public function resetTotalTerlambat(){
        $instruktur = Instruktur::all(); 
        // $total_terlambat = $instruktur->total_terlambat;

        // if(!is_null($instruktur->$total_terlambat)){
        //     return response([
        //         'message' => 'Instasdasdruktur Not Found',
        //         'date' => null
        //     ], 404);
        // } 
        
        foreach ($instruktur as $presensi) {
            // $today = Carbon::now()->toDateString();
            $today = '2023-05-01';
            if ($today == Carbon::now()->startOfMonth()->toDateString()) {
                $presensi->total_terlambat = '00:00:00';
                $presensi->save();
            }else{}
        }
        return response([
            'message' => 'set total terlambat instruktur = 0',
            'data' => $instruktur
        ], 200);
    }
}

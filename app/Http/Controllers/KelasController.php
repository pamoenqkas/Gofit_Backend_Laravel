<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Http\Resources\KelasResource;
use Illuminate\Support\Facades\Validator;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        //render view with posts
        return new KelasResource(
            true,
            'List Data Kelas',
            $kelas
        );
    }

    public function create()
    {
        return view('kelas.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_kelas' => 'required',
            'nama_kelas' => 'required',
            'harga' => 'required',
            'kapasitas' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $kelas = Kelas::create([
            'id_kelas' => $request->id_kelas,
            'nama_kelas' => $request->nama_kelas,
            'harga' => $request->harga,
            'kapasitas' => $request->kapasitas,
        ]);
        return new KelasResource(true, 'Data kelas Berhasil Ditambahkan!', $kelas);
    }

    public function edit($id)
    {
        $kelas = kelas::findOrFail($id);
        return view('kelas.edit', compact('kelas'));
    }

    public function show($id)
    {
        $kelas = Kelas::find($id);

        if (!is_null($kelas)) {
            return response([
                'message' => 'Retrieve Kelas Success',
                'data' => $kelas,
                'nama_kelas' => $kelas->nama_kelas,
            ], 200);
            // return new KelasResource(true, 'Data Ditemukan', $kelas);
        }
        return new KelasResource(true, 'Data Tidak Ditemukan', $kelas);

        // return response([
        //     'message' => 'Kelas not found',
        //     'data' => null
        // ], 400);
    }

    public function update(Request $request, $id_kelas)
    {
        $kelas = Kelas::find($id_kelas); 

        if(is_null($kelas)){
            return response([
                'message' => 'Kelas Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_kelas' => 'required',
            'harga' => 'required',
            'kapasitas' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $kelas->nama_kelas = $updateData['nama_kelas'];
        $kelas->harga = $updateData['harga'];
        $kelas->kapasitas = $updateData['kapasitas'];

        if($kelas->save()){
            return response([
                'message' => 'Update Kelas Success',
                'data' => $kelas
            ], 200);
        }

        return response([
            'message' => 'Update Kelas Failed',
            'data' => null
        ], 400);
    }


    public function destroy($id_kelas)
    {
        $kelas = Kelas::find($id_kelas); 

        if(is_null($kelas)){
            return response([
                'message' => 'Kelas Not Found',
                'date' => null
            ], 404);
        } 

        if($kelas->delete()){
            $kelas->delete();
            return response([
                'message' => 'Delete Kelas Success',
                'data' => $kelas
            ], 200);
        } //Return message saat berhasil menghapus data Kelas

        return response([
            'message' => 'Delete Kelas Failed',
            'data' => null, 
        ],400);
    }
}

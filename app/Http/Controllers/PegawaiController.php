<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Http\Resources\PegawaiResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        //render view with posts
        return new PegawaiResource(
            true,
            'List Data Pegawai',
            $pegawai
        );
    }
    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_pegawai' => 'required',
            'id_role' => 'required',
            'nama_pegawai' => 'required',
            'no_telp_pegawai' => 'required',
            'alamat_pegawai' => 'required',
            'email_pegawai' => 'required',
            'tanggal_lahir_pegawai' => 'required',
            'password' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $pegawai = Pegawai::create([
            'id_pegawai' => $request->id_pegawai,
            'id_role' => $request->id_role,
            'nama_pegawai' => $request->nama_pegawai,
            'no_telp_pegawai' => $request->no_telp_pegawai,
            'alamat_pegawai' => $request->alamat_pegawai,
            'email_pegawai' => $request->email_pegawai,
            'tanggal_lahir_pegawai' => $request->tanggal_lahir_pegawai,
            'password' => $request->tanggal_lahir_pegawai,
        ]);
        return new PegawaiResource(true, 'Data Pegawai Berhasil Ditambahkan!', $pegawai);
    }

    public function edit($id_pegawai)
    {
        $pegawai = Pegawai::findOrFail($id_pegawai);
        return view('pegawai.edit', compact('pegawai'));
    }

    public function show($id_pegawai)
    {
        $pegawai = Pegawai::find($id_pegawai);

        if(!is_null($pegawai)){
            return new PegawaiResource(true, 'Data Ditemukan', $pegawai);
        }
        return new PegawaiResource(true, 'Data Tidak Ditemukan', $pegawai);
    }

    public function update(Request $request, $id_pegawai)
    {
        $pegawai = Pegawai::find($id_pegawai); 

        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_pegawai' => 'required',
            'no_telp_pegawai' => 'required',
            'alamat_pegawai' => 'required',
            'email_pegawai' => 'required',
            'tanggal_lahir_pegawai' => 'required',
            'password' => '',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $pegawai->nama_pegawai = $updateData['nama_pegawai'];
        $pegawai->no_telp_pegawai = $updateData['no_telp_pegawai'];
        $pegawai->alamat_pegawai = $updateData['alamat_pegawai'];
        $pegawai->email_pegawai = $updateData['email_pegawai'];
        $pegawai->tanggal_lahir_pegawai = $updateData['tanggal_lahir_pegawai'];

        if($pegawai->save()){
            return response([
                'message' => 'Update pegawai Success',
                'data' => $pegawai
            ], 200);
        }

        return response([
            'message' => 'Update pegawai Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_pegawai)
    {
        $pegawai = Pegawai::find($id_pegawai); 

        if(is_null($pegawai)){
            return response([
                'message' => 'pegawai Not Found',
                'date' => null
            ], 404);
        } 

        if($pegawai->delete()){
            $pegawai->delete();
            return response([
                'message' => 'Delete pegawai Success',
                'data' => $pegawai
            ], 200);
        } //Return message saat berhasil menghapus data pegawai

        return response([
            'message' => 'Delete pegawai Failed',
            'data' => null, 
        ],400);
    }
}

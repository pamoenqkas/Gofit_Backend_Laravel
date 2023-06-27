<?php

namespace App\Http\Controllers;

use App\Models\PromoKelas;
use Illuminate\Http\Request;
use App\Http\Resources\PromoKelasResource;
use Illuminate\Support\Facades\Validator;


class PromoKelasController extends Controller
{
    public function index()
    {
        $promo_kelas = PromoKelas::all();
        //render view with posts
        return new PromoKelasResource(
            true,
            'List Data Promo kelas',
            $promo_kelas
        );
    }

    public function create()
    {
        return view('promo_kelas.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_promo_kelas' => 'required',
            'syarat_bonus_kelas' => 'required',
            'bonus_kelas' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $promo_kelas = PromoKelas::create([
            'id_promo_kelas' => $request->id_promo_kelas,
            'syarat_bonus_kelas' => $request->syarat_bonus_kelas,
            'bonus_kelas' => $request->bonus_kelas,
        ]);
        return new PromoKelasResource(true, 'Data Promo kelas Berhasil Ditambahkan!', $promo_kelas);
    }

    public function edit($id)
    {
        $promo_kelas = PromoKelas::findOrFail($id);
        return view('promo_kelas.edit', compact('promo_kelas'));
    }

    public function show($id)
    {
        $promo_kelas = PromoKelas::find($id);

        if (!is_null($promo_kelas)) {
            // return response([
            //     'message' => 'Retrieve Kelas Success',
            //     'data' => $kelas
            // ], 200);
            return new PromoKelasResource(true, 'Data Ditemukan', $promo_kelas);
        }
        return new PromoKelasResource(true, 'Data Tidak Ditemukan', $promo_kelas);

        // return response([
        //     'message' => 'Kelas not found',
        //     'data' => null
        // ], 400);
    }

    public function update(Request $request, $id_promo_kelas)
    {
        $promo_kelas = PromoKelas::find($id_promo_kelas); 

        if(is_null($promo_kelas)){
            return response([
                'message' => 'Promo Kelas Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_promo_kelas' => 'required',
            'syarat_bonus_kelas' => 'required',
            'bonus_kelas' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $promo_kelas->id_promo_kelas = $updateData['id_promo_kelas'];
        $promo_kelas->syarat_bonus_kelas = $updateData['syarat_bonus_kelas'];
        $promo_kelas->bonus_kelas = $updateData['bonus_kelas'];

        if($promo_kelas->save()){
            return response([
                'message' => 'Update Promo Kelas Success',
                'data' => $promo_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Promo Kelas Failed',
            'data' => null
        ], 400);
    }


    public function destroy($id_promo_kelas)
    {
        $promo_kelas = PromoKelas::find($id_promo_kelas); 

        if(is_null($promo_kelas)){
            return response([
                'message' => 'Promo Kelas Not Found',
                'date' => null
            ], 404);
        } 

        if($promo_kelas->delete()){
            $promo_kelas->delete();
            return response([
                'message' => 'Delete Promo Kelas Success',
                'data' => $promo_kelas
            ], 200);
        } //Return message saat berhasil menghapus data Kelas

        return response([
            'message' => 'Delete Promo Kelas Failed',
            'data' => null, 
        ],400);
    }
}

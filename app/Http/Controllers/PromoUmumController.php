<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PromoUmum;
use App\Http\Resources\PromoUmumResource;
use Illuminate\Support\Facades\Validator;

class PromoUmumController extends Controller
{
    public function index()
    {
        $promo_umum = PromoUmum::all();
        //render view with posts
        return new PromoUmumResource(
            true,
            'List Data Promo Umum',
            $promo_umum
        );
    }

    public function create()
    {
        return view('promo_umum.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_promo_umum' => 'required',
            'syarat_bonus_umum' => 'required',
            'bonus_umum' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $promo_umum = PromoUmum::create([
            'id_promo_umum' => $request->id_promo_umum,
            'syarat_bonus_umum' => $request->syarat_bonus_umum,
            'bonus_umum' => $request->bonus_umum,
        ]);
        return new PromoUmumResource(true, 'Data Promo Umum Berhasil Ditambahkan!', $promo_umum);
    }

    public function edit($id)
    {
        $promo_umum = PromoUmum::findOrFail($id);
        return view('promo_umum.edit', compact('promo_umum'));
    }

    public function show($id)
    {
        $promo_umum = PromoUmum::find($id);

        if (!is_null($promo_umum)) {
            // return response([
            //     'message' => 'Retrieve Kelas Success',
            //     'data' => $kelas
            // ], 200);
            return new PromoUmumResource(true, 'Data Ditemukan', $promo_umum);
        }
        return new PromoUmumResource(true, 'Data Tidak Ditemukan', $promo_umum);

        // return response([
        //     'message' => 'Kelas not found',
        //     'data' => null
        // ], 400);
    }

    public function update(Request $request, $id_promo_umum)
    {
        $promo_umum = PromoUmum::find($id_promo_umum); 

        if(is_null($promo_umum)){
            return response([
                'message' => 'Promo Umum Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_promo_umum' => 'required',
            'syarat_bonus_umum' => 'required',
            'bonus_umum' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $promo_umum->id_promo_umum = $updateData['id_promo_umum'];
        $promo_umum->syarat_bonus_umum = $updateData['syarat_bonus_umum'];
        $promo_umum->bonus_umum = $updateData['bonus_umum'];

        if($promo_umum->save()){
            return response([
                'message' => 'Update Promo Umum Success',
                'data' => $promo_umum
            ], 200);
        }

        return response([
            'message' => 'Update Promo Umum Failed',
            'data' => null
        ], 400);
    }


    public function destroy($id_promo_umum)
    {
        $promo_umum = PromoUmum::find($id_promo_umum); 

        if(is_null($promo_umum)){
            return response([
                'message' => 'Promo Umum Not Found',
                'date' => null
            ], 404);
        } 

        if($promo_umum->delete()){
            $promo_umum->delete();
            return response([
                'message' => 'Delete Promo Umum Success',
                'data' => $promo_umum
            ], 200);
        } //Return message saat berhasil menghapus data Kelas

        return response([
            'message' => 'Delete Promo Umum Failed',
            'data' => null, 
        ],400);
    }
}

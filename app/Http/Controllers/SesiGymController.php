<?php

namespace App\Http\Controllers;

use App\Http\Resources\SesiGymResource;
use App\Models\SesiGym;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SesiGymController extends Controller
{
    public function index()
    {
        $sesi_gym = SesiGym::all();
        //render view with posts
        return new SesiGymResource(
            true,
            'List Data SesiGym',
            $sesi_gym
        );
    }
    public function create()
    {
        return view('sesi_gym.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_sesi' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //Fungsi Post ke Database
        $sesi_gym = SesiGym::create([
            'id_sesi' => $request->id_sesi,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota' => $request->kuota,
        ]);
        return new SesiGymResource(true, 'Data Sesi Gym Berhasil Ditambahkan!', $sesi_gym);
    }

    public function edit($id_sesi)
    {
        $sesi_gym = SesiGym::findOrFail($id_sesi);
        return view('sesi.edit', compact('sesi'));
    }

    public function show($id_sesi)
    {
        $sesi = SesiGym::find($id_sesi);

        if (!is_null($sesi)) {
            return response([
                'message' => 'Retrieve sesi Success',
                'data' => $sesi
            ], 200);
        }

        return response([
            'message' => 'sesi not found',
            'data' => null
        ], 400);
    }

    public function update(Request $request, $id_sesi)
    {
        $sesi_gym = SesiGym::find($id_sesi);
        if (is_null($sesi_gym)) {
            return response([
                'message' => 'Sesi Not Found',
                'data' => null
            ], 404);
        }

        $this->validate($request, [
            'id_sesi' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'kuota' => 'required',
        ]);

        $sesi_gym = SesiGym::findOrFail($id_sesi);

        $sesi_gym->update([
            'id_sesi' => $request->id_sesi,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'kuota' => $request->kuota,
        ]);

        if ($sesi_gym) {
            return redirect()
                ->route('sesi.index')
                ->with([
                    'success' => 'Sesir has been updated successfully'
                ]);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with([
                    'error' => 'Some problem has occured, please try again'
                ]);
        }
    }

    public function destroy($id_sesi)
    {
        $sesi_gym = SesiGym::findOrFail($id_sesi);
        $sesi_gym->delete();

        if ($sesi_gym) {
            return redirect()
                ->route('sesi.index')
                ->with([
                    'success' => 'Sesi has been deleted successfully'
                ]);
        } else {
            return redirect()
                ->route('sesi.index')
                ->with([
                    'error' => 'Some problem has occurred, please try again'
                ]);
        }
    }
}

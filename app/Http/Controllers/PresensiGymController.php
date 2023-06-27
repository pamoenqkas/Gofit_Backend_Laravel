<?php

namespace App\Http\Controllers;

use App\Http\Resources\PresensiGymResource;
use Illuminate\Http\Request;
use App\Models\BookingGym;
use App\Models\PresensiGym;
use App\Models\Member;
use App\Http\Resources\PresensiResource;
use App\Models\SesiGym;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PresensiGymController extends Controller
{
    public function index()
    {
        $presensi_gym = PresensiGym::with('member', 'booking_gym.sesi_gym')->latest()->get();
        $member = Member::latest()->get();

        $booking_gym = BookingGym::latest()->first();
        $sesi_gym = SesiGym::latest()->get();


        //render view with posts
        // return new PresensiGymResource(
        //     true,
        //     'List Data Presensi Gym',
        //     $presensi_gym
        // );

        return response([
            'data' => $presensi_gym,
            // 'sesi' => $sesi_gym
        ]);
    }
    public function create()
    {
        return view('presensi_gym.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_presensi_gym' => '',
            'id_member' => '',
            'id_booking_gym' => '',
            'status' => '',
            'tanggal_presensi_gym' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // $last = PresensiGym::latest()->first();
        // if($last == null){
        //     $id_presensi_gym = 1;
        // }else{
        //     $id_presensi_gym = ((int)Str::substr($last->id_presensi_gym, 2)) + 1;
        // }

        // if($id_presensi_gym < 10){
        //     $id_presensi_gym = '0'.$id_presensi_gym;
        // }else if($id_presensi_gym < 100){
        //     $id_presensi_gym = ''.$id_presensi_gym;
        // }

        $booking_gym = BookingGym::all();
        $tanggal_presensi_gym = Carbon::now()->toDateString();
        // $booking_gym_id = DB::select('SELECT a.id_booking_gym FROM booking_gym a');
        foreach($booking_gym as $item){
            
            $storeData['id_member'] = $item->id_member;
            $storeData['id_booking_gym'] = $item->id_booking_gym ;
            $storeData['status'] = 'Belum Di Presensi';
            $storeData['tanggal_presensi_gym'] = $tanggal_presensi_gym;

            $id_temp = $item->id_booking_gym;
            $id = substr($id_temp,-2);
            
            // return response([
            //     'data' => 'PG'.$id
            // ]);
            $storeData['id_presensi_gym'] = 'PG'.$id;
            
            $presensi_gym = PresensiGym::create($storeData);
            $presensi_gym = PresensiGym::latest()->first();            
        }

        $presensi_gym = PresensiGym::all();
        return new PresensiGymResource(true, 'Data Presensi Gym Berhasil Ditambahkan!', $presensi_gym);  
    }

    public function edit($id)
    {
        $presensi_gym = PresensiGym::findOrFail($id);
        return view('presensi_gym.edit', compact('presensi_gym'));
    }

    public function show($id_presensi_gym)
    {
        $presensi_gym = PresensiGym::find($id_presensi_gym);

        if(!is_null($presensi_gym)){
            return new PresensiGymResource(true, 'Data Ditemukan', $presensi_gym);
        }
        return new PresensiGymResource(true, 'Data Tidak Ditemukan', $presensi_gym);
    }

    public function update(Request $request, $id_presensi_gym)
    {
        $presensi_gym = PresensiGym::find($id_presensi_gym);

        if (is_null($presensi_gym)) {
            return response([
                'message' => 'Presensi Gym Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_presensi_gym' => '',
            'id_member' => 'required',
            'id_booking_gym' => 'required',
            'status' => 'required',
            'tanggal_presensi_gym' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $presensi_gym->id_presensi_gym = $updateData['id_presensi_gym'];
        $presensi_gym->id_member = $updateData['id_member'];
        $presensi_gym->id_booking_gym = $updateData['id_booking_gym'];
        $presensi_gym->status = $updateData['status'];
        $presensi_gym->tanggal_presensi_gym = $updateData['tanggal_presensi_gym'];
        if($presensi_gym->save()){
            return response([
                'message' => 'Update Presensi Gym Success',
                'data' => $presensi_gym
            ], 200);
        }

        return response([
            'message' => 'Update Presensi Gym Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_presensi_gym)
    {
        $presensi_gym = PresensiGym::find($id_presensi_gym); 

        if(is_null($presensi_gym)){
            return response([
                'message' => 'Presensi Gym Not Found',
                'date' => null
            ], 404);
        } 

        if($presensi_gym->delete()){
            $presensi_gym->delete();
            return response([
                'message' => 'Delete presensi Gym Success',
                'data' => $presensi_gym
            ], 200);
        }

        return response([
            'message' => 'Delete Presensi Gym Failed',
            'data' => null, 
        ],400);
    }

    public function presensiMemberGym($id_presensi_gym){
        $presensi_gym = PresensiGym::find($id_presensi_gym);
        
        if(is_null($presensi_gym)){
            return response([
                'message' => 'Presensi Gym Not Found',
                'date' => null
            ], 404);
        } 

        $presensiGym = PresensiGym::findOrFail($id_presensi_gym);
        // $sesi_jam_mulai = $presensiGym->bookingGym->sesi->jam_mulai;
        // $sesi_jam_selesai = $presensiGym->bookingGym->sesi->jam_selesai;
        // $sesiId = $presensiGym->bookingGym->sesi->id_sesi;
        // Lakukan operasi lain dengan $sesiId sesuai kebutuhan Anda

        // return $sesiId
        
        $tanggal_presensi_gym = Carbon::now()->toDateString();

        if ($presensi_gym->status == 'Belum Hadir') {
            // Ubah nilai status menjadi "Sudah di presensi"
            // $member = Member::where('id_member', $presensi_gym->id_member)->first();
            $presensi_gym->status = "Hadir";
            if($presensi_gym->save()){
                return response([
                    'message' => 'Update instruktur Success',
                    'data' => $presensi_gym,
                    // 'jam_mulai' => $sesi_jam_mulai,
                    // 'jam_selesai' => $sesi_jam_selesai,
                    'tanggal_presensi_gym' => $tanggal_presensi_gym
                ], 200);
            }
            return response([
                'message' => 'Member Berhasil di Presensi',
            ],400);
        } else {
            // Tambahkan pesan jika data tidak ditemukan
            return response([
                'message' => 'Member Gagal di Presensi',
                'data' => null, 
            ],400);
        }

        return response([
            'message' => 'Delete Presensi Gym Failed',
            'data' => null, 
        ],400);
    }

}

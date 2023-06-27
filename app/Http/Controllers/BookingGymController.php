<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingGymResource;
use App\Models\BookingGym;
use App\Models\Member;
use App\Models\SesiGym;
use Illuminate\Http\Request;
use App\Models\BookingKelas;
use App\Models\JadwalHarian;
use App\Models\Kelas;
use App\Models\PresensiGym;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingGymController extends Controller
{
    public function index()
    {
        $booking_gym = BookingGym::with('member', 'sesi_gym')->latest()->get();
        $member = Member::latest()->get();
        $sesi = SesiGym::latest()->get();

        //render view with posts
        return new BookingGymResource(
            true,
            'List Data Booking Gym',
            $booking_gym
        );
    }

    public function create()
    {
        return view('booking_gym.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            // 'id_booking_gym' => '',
            'id_member' => '',
            'id_sesi' => '',            
            'tanggal_booking_gym' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // if ($validate->fails()) {
        //     return response()->json($validate->errors(), 422);
        // }

        //Generate Id
        $last = BookingGym::latest()->first();
        if($last == null){
            $count = 1;
        }else{
            $count = ((int)Str::substr($last->id_booking_gym, 6,3)) + 1;
        }

        if($count < 10){
            $id = '0'.$count;
        }else if($count < 100){
            $id = ''.$count;
        }

        $member = $request->id_member;
        $findMember = Member::find($member);
        
        $sesi = $request->id_sesi;
        $findSesi = SesiGym::find($sesi);
    
        $presensi_gym = $request->id_presensi_gym;
        $findPresensiGym = PresensiGym::find($presensi_gym);

        $tanggal_booking_gym = Carbon::now()->toDateString();

        $curdate = Carbon::now()->format('Y-m-d');
        $month = Str::substr($curdate, 5, 2);
        $year = Str::substr($curdate, 2, 2);
        Str::substr($year, -2);
        // $masa_membership = Carbon::now()->addYear();

        $tanggal_lahir = $request->tanggal_lahir;
        $date = Str::substr($tanggal_lahir, 8, 2);
        $bulan = Str::substr($tanggal_lahir, 5, 2);
        $tahun = Str::substr($tanggal_lahir, 2, 2);
        Str::substr($tahun, -2); 

        //Periska status member
        if ($findMember->status == 'Aktif'){
            //Periksa apakah kapasitas di kelas != null
            if($findSesi->kuota != 0){
                $booking_gym = BookingGym::create([
                    'id_booking_gym' => $year.'.'.$month.'.'.$id,                
                    'id_member' => $member,
                    'id_sesi' => $sesi,
                    'tanggal_booking_gym' => $tanggal_booking_gym,
                ]);
                $presensi_gym = PresensiGym::create([
                    'id_presensi_gym' => $year.'.'.$month.'.'.$id, 
                    'id_member' => $member,
                    'id_booking_gym' => $year.'.'.$month.'.'.$id,   
                    'status' => 'Belum Hadir',
                    'tanggal_presensi_gym' => $tanggal_booking_gym,
                ]);
                    $findSesi->kuota = $findSesi->kuota - 1;
                    $findSesi->save();
                $booking_gym = BookingGym::latest()->first();
                return new BookingGymResource(true, 'Data Booking Gym Berhasil Ditambahkan', $booking_gym);
            }else{
                $booking_gym = BookingGym::latest()->first();
                return new BookingGymResource(true, 'Kapasitas Booking Pada Sesi Gym Habis!', null);
            }
        }else{
            $booking_gym = BookingGym::latest()->first();
            return new BookingGymResource(true, 'Member Tidak Aktif!', null);
        }
    }

    public function edit($id)
    {
        $booking_gym = BookingGym::findOrFail($id);
        return view('booking_gym.edit', compact('booking_Gym'));
    }
    
    public function show($id_booking_gym)
    {
        $booking_gym = BookingGym::find($id_booking_gym);
        $member = Member::all();
        $sesi = SesiGym::all();

        if(!is_null($booking_gym)){
            return new BookingGymResource(true, 'Data Ditemukan', $booking_gym);
        }
        return new BookingGymResource(true, 'Data Tidak Ditemukan', $booking_gym);
    }

    public function update(Request $request, $id_booking_gym)
    {
        $booking_gym = BookingGym::find($id_booking_gym); 

        if(is_null($booking_gym)){
            return response([
                'message' => 'Booking Gym Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_booking_gym' => '',
            'id_member' => 'required',
            'id_sesi' => 'required',            
            'tanggal_booking_gym' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $booking_gym->id_member = $updateData['id_member'];
        $booking_gym->id_sesi = $updateData['id_sesi'];
        $booking_gym->tanggal_booking_gym = $updateData['tanggal_booking_gym'];

        if($booking_gym->save()){
            return response([
                'message' => 'Update Booking Gym Success',
                'data' => $booking_gym
            ], 200);
        }

        return response([
            'message' => 'Update Booking Gym Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_booking_gym)
    {
        $booking_gym = BookingGym::find($id_booking_gym); 

        if(is_null($booking_gym)){
            return response([
                'message' => 'Booking Gym Not Found',
                'date' => null
            ], 404);
        } 
        
        //set today
        $today = Carbon::now()->toDateString();
        //deklrasai booking kelas
        $bookingToday = $booking_gym->tanggal_booking_gym;
        //deklarasi kemarin dari tanggal booking 
        // $bookingKurangToday = $bookingToday->subday()->toDateString();
        $tanggal_booking_kurang = date('Y-m-d', strtotime('-1 day', strtotime($bookingToday)));

        if($today <= $tanggal_booking_kurang){
            if($booking_gym->delete()){
                $booking_gym->delete();
                    // Hapus data dari Table B (presensi_gym) yang memiliki id_booking yang sama
                PresensiGym::where('id_booking_gym', $booking_gym->id_booking_gym)->delete();
                return response([
                    'message' => 'Delete Booking Gym Success',
                    'data' => $booking_gym
                ], 200);
            }
        }else{}

        return response([
            'message' => 'Delete Booking Gym Maksimal H-1',
            'data' => null, 
        ],400);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingKelasResource;
use App\Models\BookingKelas;
use Illuminate\Http\Request;
use App\Models\JadwalHarian;
use App\Models\Member;
use App\Models\Kelas;
use App\Models\DepositKelas;
use App\Models\PresensiKelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BookingKelasController extends Controller
{
    public function index()
    {
        $booking_kelas = BookingKelas::with('member', 'kelas', 'jadwal_harian')->latest()->get();
        $member = Member::latest()->get();
        $jadwal_harian = JadwalHarian::latest()->get();
        $kelas = Kelas::latest()->get();

        //render view with posts
        return new BookingKelasResource(
            true,
            'List Data Booking Kelas',
            $booking_kelas
        );

        
    }

    public function create()
    {
        return view('booking_kelas.create');
    }

    public function store(Request $request)
    {

        // $member = Member::findOrFail($id_member)->get();

        // $storeData = $request->all();
        // $validate = Validator::make($storeData, [
        //     'id_member' => 'required',
        // ]);
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_booking_kelas' => '',
            'id_member' => '',
            'id_jadwal_harian' => '',            
            'id_kelas' => '',
            'tanggal_booking_kelas' => '',
            'metode_pembayaran' => '',
            // 'masa_aktif' => 'required',
            // 'aktivasi_tahunan' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // if ($validate->fails()) {
        //     return response()->json($validate->errors(), 422);
        // }

        //Generate Id
        $last = BookingKelas::latest()->first();
        if($last == null){
            $count = 1;
        }else{
            $count = ((int)Str::substr($last->id_booking_kelas, 6,3)) + 1;
        }

        if($count < 10){
            $id = '0'.$count;
        }else if($count < 100){
            $id = ''.$count;
        }

        $curdate = Carbon::now()->format('Y-m-d');
        $month = Str::substr($curdate, 5, 2);
        $year = Str::substr($curdate, 2, 2);
        Str::substr($year, -2);
        
        // $masa_membership = Carbon::now()->addYear();

        // $member = $request->aktivasi_tahunan;
        // $member->save();
        // $tanggalExpired = $request -> tanggal->addYear(1);

        $tanggalNow = Carbon::parse($request->tanggal);
        // $tanggalExpired = $tanggalNow->addYear();

        // $member = Member::findOrFail($id_member);
        $member = $request->id_member;
        $findMember = Member::find($member);

        $findDepositKelas = DepositKelas::where('id_member', $findMember->id_member)->first();

        $kelas = $request->id_kelas;
        $findKelas = Kelas::find($kelas);
        
        $jadwal_harian = $request->id_jadwal_harian;
        $findJadwalHarian = JadwalHarian::find($jadwal_harian);        
        // $masa_membership = $member->masa_membership;
        
        //Fungsi Post ke Database
        $tanggal_booking_kelas = Carbon::now()->toDateString();

        //Periska status member
        if ($findMember->status == 'Aktif'){
            //Periksa apakah kapasitas di kelas != null
            if($findKelas->kapasitas != 0){
                //kapasitas kurang 1 (update di table kelas kapasitas berkurang)
                $kapasitasKelas = $findKelas->kapasitas - 1;
                //Periksa deposit kelas member != null
                if($findMember->deposit_kelas > 0){
                    $depositKelas = $findMember->deposit_kelas - 1;
                    // $tanggalFromJadwal = $findJadwalHarian->tanggal;

                    // Nambah Presensi Kelas setelah Melakukan Booking Kelas
                    $booking_kelas = BookingKelas::create([
                        'id_booking_kelas' => $year.'.'.$month.'.'.$id,  
                        'id_member' => $request->id_member,
                        'id_jadwal_harian' => $request->id_jadwal_harian,
                        'id_kelas' => $request->id_kelas,
                        'tanggal_booking_kelas' => $tanggal_booking_kelas,
                        'metode_pembayaran' => 'Deposit Kelas'
                    ]);
                    $booking_kelas = PresensiKelas::create([
                        'id_presensi_kelas' => $year.'.'.$month.'.'.$id,  
                        'id_booking_kelas' => $year.'.'.$month.'.'.$id,                 
                        'id_member' => $request->id_member,
                        'tanggal_presensi_kelas' => $tanggal_booking_kelas,
                        'status' => 'Belum Hadir'
                    ]);

                    $findMember->deposit_kelas = $depositKelas;
                    $findMember->save();
                    $findKelas->kapasitas = $kapasitasKelas;
                    $findKelas->save();
                    $booking_kelas = BookingKelas::latest()->first();

                    return response([
                        'data' => $booking_kelas,
                        'message' => 'Data Booking Kelas Berhasil Ditambahkan Menggunakan Deposit Kelas!'
                    ]);

                    // return new BookingKelasResource(true, 'Data Booking Kelas Berhasil Ditambahkan Menggunakan Deposit Kelas', $booking_kelas);

                //periksa deposit uang member >= harga kelas
                }else if($findMember->deposit >= $findKelas->harga){
                    // $findMember->deposit = $findMember->deposit - $findKelas->harga;
                    $depositUang = $findMember->deposit - $findKelas->harga;

                    $booking_kelas = BookingKelas::create([
                        'id_booking_kelas' => $year.'.'.$month.'.'.$id,  
                        'id_member' => $request->id_member,
                        'id_jadwal_harian' => $request->id_jadwal_harian,
                        'id_kelas' => $request->id_kelas,
                        'tanggal_booking_kelas' => $tanggal_booking_kelas,
                        'metode_pembayaran' => 'Deposit Umum'
                    ]);
                    $booking_kelas = PresensiKelas::create([
                        'id_presensi_kelas' => $year.'.'.$month.'.'.$id,  
                        'id_booking_kelas' => $year.'.'.$month.'.'.$id,            
                        'id_member' => $request->id_member,
                        'tanggal_presensi_kelas' => $tanggal_booking_kelas,
                        'status' => 'Belum Hadir'
                    ]);

                    $findMember->deposit = $depositUang;
                    $findMember->save();
                    $booking_kelas = BookingKelas::latest()->first();
                    
                    return response([
                        'data' => $booking_kelas,
                        'message' => 'Data Booking Kelas Berhasil Ditambahkan Menggunakan Deposit Uang!'
                    ]);
                }else{
                    $booking_kelas = BookingKelas::latest()->first();
                    return response([
                        'data' => null,
                        'message' => 'Member Tidak Memiliki Deposit Kelas dan Uang!'
                    ]);
                }             
            }else{
                $booking_kelas = BookingKelas::latest()->first();
                return response([
                    'data' => null,
                    'message' => 'Kapasitas Kelas Habis!!'
                ]);
            }
        }else{
            $booking_kelas = BookingKelas::latest()->first();
            return response([
                'data' => null,
                'message' => 'Member Tidak Aktif!'
            ]);
        }
    }

    public function edit($id)
    {
        $booking_kelas = BookingKelas::findOrFail($id);
        return view('booking_kelas.edit', compact('booking_kelas'));
    }
    
    public function show($id_booking_kelas)
    {
        $booking_kelas = BookingKelas::find($id_booking_kelas);
        $member = Member::all();
        $kelas = Kelas::all();
        $jadwal_harian = JadwalHarian::all();

        if(!is_null($booking_kelas)){
            return new BookingKelasResource(true, 'Data Ditemukan', $booking_kelas);
        }
        return new BookingKelasResource(true, 'Data Tidak Ditemukan', $booking_kelas);
    }

    public function update(Request $request, $id_booking_kelas)
    {
        $booking_kelas = BookingKelas::find($id_booking_kelas); 

        if(is_null($booking_kelas)){
            return response([
                'message' => 'Booking Kelas Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_booking_kelas' => '',
            'id_member' => 'required',
            'id_jadwal_harian' => 'required',            
            'id_kelas' => 'required',
            'tanggal_booking_kelas' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $booking_kelas->id_member = $updateData['id_member'];
        $booking_kelas->id_jadwal_harian = $updateData['id_jadwal_harian'];
        $booking_kelas->id_kelas = $updateData['id_member'];
        $booking_kelas->tanggal_booking_kelas = $updateData['tanggal_booking_kelas'];

        if($booking_kelas->save()){
            return response([
                'message' => 'Update Booking Kelas Success',
                'data' => $booking_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Booking Kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_booking_kelas)
    {
        $booking_kelas = BookingKelas::find($id_booking_kelas); 

        if(is_null($booking_kelas)){
            return response([
                'message' => 'Booking Kelas Not Found',
                'date' => null
            ], 404);
        } 
        
        //set today
        $today = Carbon::now()->toDateString();
        //deklrasai booking kelas
        $bookingToday = $booking_kelas->tanggal_booking_kelas;
        //deklarasi kemarin dari tanggal booking 
        // $bookingKurangToday = $bookingToday->subday()->toDateString();
        $tanggal_booking_kurang = date('Y-m-d', strtotime('-1 day', strtotime($bookingToday)));

        if($today <= $tanggal_booking_kurang){
            if($booking_kelas->delete()){
                $booking_kelas->delete();
                return response([
                    'message' => 'Delete Booking Kelas Success',
                    'data' => $booking_kelas
                ], 200);
            }
        }else{}

        return response([
            'message' => 'Delete Booking Kelas Maksimal H-1',
            'data' => null, 
        ],400);
    }
}

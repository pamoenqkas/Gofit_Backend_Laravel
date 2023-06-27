<?php

namespace App\Http\Controllers;

use App\Http\Resources\PresensiKelasResource;
use Illuminate\Http\Request;
use App\Models\BookingKelas;
use App\Models\DepositKelas;
use App\Models\PresensiKelas;
use App\Models\Member;
use App\Models\JadwalHarian;
use App\Models\Instruktur;
use App\Models\Kelas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;


class PresensiKelasController extends Controller
{
    public function index()
    {
        $presensi_kelas = PresensiKelas::with('member', 'booking_kelas.jadwal_harian', 'booking_kelas.kelas', 'booking_kelas.jadwal_harian.instruktur')->latest()->get();
        $member = Member::latest()->get();
        
        $booking_kelas = BookingKelas::latest()->first();
        $jadwal_harian = JadwalHarian::latest()->get();
        $kelas = Kelas::latest()->get();
        $instruktur = Instruktur::latest()->get();
        $deposit_kelas = DepositKelas::latest()->get();
        //render view with posts
        return new PresensiKelasResource(
            true,
            'List Data Presensi Kelas',
            $presensi_kelas
        );
    }
    public function create()
    {
        return view('presensi_kelas.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_presensi_kelas' => '',
            'id_booking_kelas' => '',   
            'id_member' => '',            
            'tanggal_presensi_kelas' => '',
            'status' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $booking_kelas = BookingKelas::all();
        // $booking_gym_id = DB::select('SELECT a.id_booking_gym FROM booking_gym a');
        foreach($booking_kelas as $item){
            $storeData['id_booking_kelas'] = $item->id_booking_kelas ;
            $storeData['id_member'] = $item->id_member;
            $storeData['tanggal_presensi_kelas'] = Carbon::now()->toDateString();
            $storeData['status'] = 'Hadir';

            $id_temp = $item->id_booking_kelas;
            $id = substr($id_temp,-2);
            
            $storeData['id_presensi_kelas'] = 'PK'.$id;
            
            $presensi_kelas = PresensiKelas::create($storeData);
            $presensi_kelas = PresensiKelas::latest()->first();            
        }

        $presensi_kelas = PresensiKelas::all();
        return new PresensiKelasResource(true, 'Data Presensi Kelas Berhasil Ditambahkan!', $presensi_kelas);  
    }

    public function edit($id)
    {
        $presensi_kelas = PresensiKelas::findOrFail($id);
        return view('presensi_kelas.edit', compact('presensi_kelas'));
    }

    public function show($id_presensi_kelas)
    {
        $presensi_kelas = PresensiKelas::find($id_presensi_kelas);

        if(!is_null($presensi_kelas)){
            return new PresensiKelasResource(true, 'Data Ditemukan', $presensi_kelas);
        }
        return new PresensiKelasResource(true, 'Data Tidak Ditemukan', $presensi_kelas);
    }

    public function update($id_presensi_kelas)
    {
        $presensi_kelas = PresensiKelas::find($id_presensi_kelas);

        if (is_null($presensi_kelas)) {
            return response([
                'message' => 'Presensi Kelas Not Found',
                'data' => null
            ], 404);
        }

        // $presensiKelas = PresensiKelas::findOrFail($presensi_kelas->id_booking_kelas)->first();

        $bookingKelas = BookingKelas::findOrFail($presensi_kelas->id_booking_kelas)->first();
        $jadwalHarian = JadwalHarian::findOrFail($bookingKelas->id_jadwal_harian)->first();
        $kelas = Kelas::findOrFail($jadwalHarian->id_kelas)->first();
        $member = Member::findOrFail($presensi_kelas->id_member)->first();
        $hargaKelas = $kelas->harga;
        $idMember = $member->id_member;

        // $member = $request->id_member;
        // $findMember = Member::find($member);

        // return response([
        //     'data' => $idMember
        // ]);

        if($bookingKelas->metode_pembayaran = 'Deposit Kelas'){
            // $findMember->deposit = $findMember->deposit - $hargaKelas;
            // $findMember->save();
            $member->deposit_kelas = $member->deposit_kelas - 1;
            $presensi_kelas->status = 'Hadir';
            $member->save();
            if($presensi_kelas->save()){
                return response([
                    'message' => 'Update Presensi Instruktur Success',
                    'sisa_deposit_kelas' => $presensi_kelas
                    // 'berlaku_sampai' => $deposit_kelas->masa_berlaku,
                ], 200);
            }
        }else if ($bookingKelas->metode_pembayaran = 'Deposit Umum'){
            $member->deposit = $member->deposit - $hargaKelas;
            $member->save();
            if($presensi_kelas->save()){
                return response([
                    'message' => 'Update Presensi Instruktur Success',
                    'tarif' => $hargaKelas,
                    'sisa_deposit' => $member->deposit_kelas

                ], 200);
            }
        
        }else{}


        // $presensi_kelas->id_presensi_kelas = $updateData['id_presensi_kelas'];
        // $presensi_kelas->id_booking_kelas = $updateData['id_booking_kelas'];
        // $presensi_kelas->id_member = $updateData['id_member'];
        // $presensi_kelas->tanggal_presensi_kelas = $updateData['tanggal_presensi_kelas'];
        // $presensi_kelas->tarif = $updateData['tarif'];
        // $presensi_kelas->sisa_deposit = $updateData['sisa_deposit'];
        if($presensi_kelas->save()){
            return response([
                'message' => 'Update Presensi Kelas Success',
                'data' => $presensi_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Presensi Kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_presensi_kelas)
    {
        $presensi_kelas = PresensiKelas::find($id_presensi_kelas); 

        if(is_null($presensi_kelas)){
            return response([
                'message' => 'Presensi Kelas Not Found',
                'date' => null
            ], 404);
        } 

        if($presensi_kelas->delete()){
            $presensi_kelas->delete();
            return response([
                'message' => 'Delete presensi Kelas Success',
                'data' => $presensi_kelas
            ], 200);
        }

        return response([
            'message' => 'Delete Presensi Kelas Failed',
            'data' => null, 
        ],400);
    }
}

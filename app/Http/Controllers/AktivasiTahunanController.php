<?php

namespace App\Http\Controllers;

use App\Http\Resources\AktivasiTahunanResource;
use Illuminate\Http\Request;
use App\Models\AktivasiTahunan;
use App\Models\LaporanPendapatanBulanan;
use App\Models\Member;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;


class AktivasiTahunanController extends Controller
{
    public function index()
    {
        $aktivasi_tahunan = AktivasiTahunan::with('member', 'pegawai')->latest()->get();
        $member = Member::latest()->get();
        $pegawai = Pegawai::latest()->get();

        //render view with posts
        return new AktivasiTahunanResource(
            true,
            'List Data Aktivasi Tahunan',
            $aktivasi_tahunan
        );
    }

    public function create()
    {
        return view('aktivasi_tahunan.create');
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
            'id_aktivasi_tahunan' => '',
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'tanggal' => '',
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
        $last = AktivasiTahunan::latest()->first();
        if($last == null){
            $id_aktivasi_tahunan = 1;
        }else{
            $id_aktivasi_tahunan = ((int)Str::substr($last->id_aktivasi_tahunan, 6,3)) + 1;
        }

        if($id_aktivasi_tahunan < 10){
            $id_aktivasi_tahunan = '00'.$id_aktivasi_tahunan;
        }else if($id_aktivasi_tahunan <100){
            $id_aktivasi_tahunan = '0'.$id_aktivasi_tahunan;
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
        $tanggalExpired = $tanggalNow->addYear();

        // $member = Member::findOrFail($id_member);
        $member = $request->id_member;
        $findMember = Member::find($member);

        //Inputan sekrang 
        $bulanSekarang = Carbon::now()->format('m');

        //kondisi updat diimana bulanSekarang == memeriksa bulan pada table Laporan yang sama dengan bulan sekarang

        $bulanJanuari = Carbon::parse('January')->startOfMonth();

        $laporanJanuari = LaporanPendapatanBulanan::whereMonth('bulan', $bulanJanuari->format('m'))->get();
        
        // $masa_membership = $member->masa_membership;
        
        //Fungsi Post ke Database
        $aktivasi_tahunan = AktivasiTahunan::create([
            'id_aktivasi_tahunan' => $year.'.'.$month.'.'.$id_aktivasi_tahunan,
            'id_pegawai' => $request->id_pegawai,
            'id_member' => $request->id_member,
            'tanggal' => Carbon::now()->toDateString(),
            'masa_aktif' => $tanggalExpired,
            'aktivasi_tahunan' => 3000000,
        ]);


        // $member = $aktivasi_tahunan->member;
        $findMember->masa_membership = $tanggalExpired;
        $findMember->status = 'Aktif';
        $findMember->save();
        $aktivasi_tahunan = AktivasiTahunan::latest()->first();
        return new AktivasiTahunanResource(true, 'Data Aktivasi Tahunan Berhasil Ditambahkan!', $aktivasi_tahunan);
    }

    public function edit($id)
    {
        $aktivasi_tahunan = AktivasiTahunan::findOrFail($id);
        return view('aktivasi_tahunan.edit', compact('aktivasi_tahunan'));
    }
    
    public function show($id_aktivasi_tahunan)
    {
        $aktivasi_tahunan = AktivasiTahunan::find($id_aktivasi_tahunan);
        $member = Member::all();
        $pegawai = Pegawai::all();

        if(!is_null($aktivasi_tahunan)){
            return new AktivasiTahunanResource(true, 'Data Ditemukan', $aktivasi_tahunan);
        }
        return new AktivasiTahunanResource(true, 'Data Tidak Ditemukan', $aktivasi_tahunan);
    }

    public function update(Request $request, $id_aktivasi_tahunan)
    {
        $aktivasi_tahunan = AktivasiTahunan::find($id_aktivasi_tahunan); 

        if(is_null($aktivasi_tahunan)){
            return response([
                'message' => 'aktivasi tahunan Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'tanggal' => 'required',
            'masa_aktif' => 'required',
            'aktivasi_tahunan' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $aktivasi_tahunan->id_pegawai = $updateData['id_pegawai'];
        $aktivasi_tahunan->id_member = $updateData['id_member'];
        $aktivasi_tahunan->tanggal = $updateData['tanggal'];
        $aktivasi_tahunan->masa_aktif = $updateData['masa_aktif'];
        $aktivasi_tahunan->aktivasi_tahunan = $updateData['aktivasi_tahunan'];

        if($aktivasi_tahunan->save()){
            return response([
                'message' => 'Update Aktivasi Tahunan Success',
                'data' => $aktivasi_tahunan
            ], 200);
        }

        return response([
            'message' => 'Update Aktivasi Tahunan Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_aktivasi_tahunan)
    {
        $aktivasi_tahunan = AktivasiTahunan::find($id_aktivasi_tahunan); 

        if(is_null($aktivasi_tahunan)){
            return response([
                'message' => 'Aktivasi Tahunan Not Found',
                'date' => null
            ], 404);
        } 

        if($aktivasi_tahunan->delete()){
            $aktivasi_tahunan->delete();
            return response([
                'message' => 'Delete Aktivasi Tahunan Success',
                'data' => $aktivasi_tahunan
            ], 200);
        }

        return response([
            'message' => 'Delete Aktivasi Tahunan Failed',
            'data' => null, 
        ],400);
    }
}

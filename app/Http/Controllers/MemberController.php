<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Http\Resources\MemberResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MemberController extends Controller
{
    public function index()
    {
        $member = Member::all();
        //render view with posts

        return response([
            'data' => $member,
        ]);
        // return new MemberResource(
        //     true,
        //     'List Data Member',
        //     $member
        // );
    }
    public function create()
    {
        return view('member.create');
    }

    public function store(Request $request)
    {
        //Validasi Formulir
        $validator = Validator::make($request->all(), [
            'id_member' => '',
            'nama_member' => 'required',
            'no_telp_member' => 'required',
            'alamat_member' => 'required',
            'email_member' => 'required',
            'tanggal_lahir' => 'required',
            'deposit' => 'required',
            'deposit_kelas' => 'required',
            'masa_membership' => '',
            'tanggal_daftar' => '',
            'status' => '',
            'password' => '',
            'masa_berlaku_kelas' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $last = Member::latest()->first();
        if($last == null){
            $id_member = 1;
        }else{
            $id_member = ((int)Str::substr($last->id_member, 6,3)) + 1;
        }

        if($id_member < 10){
            $id_member = '00'.$id_member;
        }else if($id_member < 100){
            $id_member = '0'.$id_member;
        }

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

        //Masa Berlaku Default
        $masa_berlaku_kelas = Carbon::now()->toDateString();
        //Fungsi Post ke Database
        $member = Member::create([
            'id_member' => $year.'.'.$month.'.'.$id_member,
            'nama_member' => $request->nama_member,
            'no_telp_member' => $request->no_telp_member,
            'alamat_member' => $request->alamat_member,
            'email_member' => $request->email_member,
            'tanggal_lahir' => $request->tanggal_lahir,
            'deposit' => $request->deposit,
            'deposit_kelas' => $request->deposit_kelas,
            'masa_membership' => null,
            'tanggal_daftar' => Carbon::now()->toDateString(),
            'status' => 'Tidak Aktif',
            'password' => $request->$date.$bulan.$year,
            'masa_berlaku' => $masa_berlaku_kelas
            
        ]);
        return new MemberResource(true, 'Data Member Berhasil Ditambahkan!', $member);
    }

    public function edit($id)
    {
        $member = Member::findOrFail($id);
        return view('member.edit', compact('member'));
    }

    public function show($id_member)
    {
        $member = Member::find($id_member);

        if(!is_null($member)){
            return new MemberResource(true, 'Data Ditemukan', $member);
        }
        return new MemberResource(true, 'Data Tidak Ditemukan', $member);
    }

    public function update(Request $request, $id_member)
    {
        $member = Member::find($id_member);

        if (is_null($member)) {
            return response([
                'message' => 'Member Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_member' => 'required',
            'no_telp_member' => 'required',
            'alamat_member' => 'required',
            'email_member' => 'required',
            'tanggal_lahir' => 'required',
            'deposit' => 'required',
            'masa_membership' => '',
            'tanggal_daftar' => 'required',
            'status' => '',
            'password' => '',
            'masa_berlaku_kelas' => '',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }

        $member->nama_member = $updateData['nama_member'];
        $member->no_telp_member = $updateData['no_telp_member'];
        $member->alamat_member = $updateData['alamat_member'];
        $member->email_member = $updateData['email_member'];
        $member->tanggal_lahir = $updateData['tanggal_lahir'];
        $member->deposit = $updateData['deposit'];
        $member->masa_membership = $updateData['masa_membership'];
        $member->tanggal_daftar = $updateData['tanggal_daftar'];
        $member->status = $updateData['status'];
        $member->masa_berlaku_kelas = $updateData['masa_berlaku_kelas'];

        if($member->save()){
            return response([
                'message' => 'Update member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Update member Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_member)
    {
        $member = Member::find($id_member); 

        if(is_null($member)){
            return response([
                'message' => 'Member Not Found',
                'date' => null
            ], 404);
        } 

        if($member->delete()){
            $member->delete();
            return response([
                'message' => 'Delete member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Delete member Failed',
            'data' => null, 
        ],400);
    }

    public function expiredToday(){
        $members = Member::all();
        
        foreach ($members as $member) {
            $today = Carbon::now()->toDateString();
            $members = Member::whereDate('masa_membership', $today)->get();
            $masaMembership = $member->masa_membership;
            // $today = Carbon::now()->toDateString();
            if ($masaMembership == $today) {
                return response([
                    'message' => 'Expired Member Hari Ini',
                    'data' => $members
                ], 200);
            }else{
                return response([
                    'message' => 'Tidak Ada Member Yang Expired ',
                    'data' => null
                ], 200);
            }
        }
    }

    public function resetExpiredToday(){
        $members = Member::all(); 

        foreach ($members as $member) {
            $today = Carbon::now()->toDateString();
            $members = Member::whereDate('masa_membership', $today)->get();
            $masaMembership = $member->masa_membership;
            if ($masaMembership == $today) {
                $member->masa_membership = null;
                $member->status = 'Tidak Aktif';
                $member->save();
            }else{
                // return response([
                //     'message' => 'Tidak Ada Member Yang Expired ',
                //     'data' => null
                // ], 200);
            }
        }
        return response([
            'message' => 'Deaktivasi Member Success',
            'data' => $members
        ], 200);
    }
}

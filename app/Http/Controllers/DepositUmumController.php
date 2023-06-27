<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepositUmumResource;
use Illuminate\Http\Request;
use App\Models\DepositUmum;
use App\Models\PromoUmum;
use App\Models\Member;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;


class DepositUmumController extends Controller
{
    public function index()
    {
        $deposit_umum = DepositUmum::with('member', 'pegawai')->latest()->get();
        $member = Member::latest()->get();
        $pegawai = Pegawai::latest()->get();

        //render view with posts
        return new DepositUmumResource(
            true,
            'List Data Deposit Umum',
            $deposit_umum
        );
    }

    public function create()
    {
        return view('deposit_umum.create');
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
            'id_deposit_umum' => '',
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'id_promo_umum' => '',
            'tanggal' => 'required',
            'deposit' => 'required|gte:500000',
            'total_deposit' => '',
            'bonus_deposit' => '',
            'sisa_deposit' => '',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // if ($validate->fails()) {
        //     return response()->json($validate->errors(), 422);
        // }

        //Generate Id
        $last = DepositUmum::latest()->first();
        if($last == null){
            $id_deposit_umum = 1;
        }else{
            $id_deposit_umum = ((int)Str::substr($last->id_deposit_umum, 6,3)) + 1;
        }

        if($id_deposit_umum < 10){
            $id_deposit_umum = '00'.$id_deposit_umum;
        }else if($id_deposit_umum <100){
            $id_deposit_umum = '0'.$id_deposit_umum;
        }

        $curdate = Carbon::now()->format('Y-m-d');
        $month = Str::substr($curdate, 5, 2);
        $year = Str::substr($curdate, 2, 2);
        Str::substr($year, -2);

        //check bonus 
        $deposit = $request->input('deposit');

        // Mendapatkan nilai bonus_umum dari tabel promo_umum
        $promo_umum = PromoUmum::where('id_promo_umum', 'PU01')->first();
        $syarat_bonus_umum = $promo_umum->syarat_bonus_umum;
        $bonus_umum = $promo_umum->bonus_umum;
        // $bonus_deposit = $promo_umum->bonus_deposit;

        // $member = Member::where('id_member', $id_member)->first();

        // $member = Member::findOrFail($id_member);
        $member = $request->id_member;
        $findMember = Member::find($member);
        $depositTableMember = $findMember->deposit;
        // $sisa_deposit = $member->sisa_deposit;
        // return response([
        //     'data' => $member[0]->$deposit,
        // ]);
        // $member = $request->input('id_member');
        // $sisa_deposit = $member->$deposit;

        // Memeriksa apakah bonus_deposit lebih besar, lebih kecil, atau sama dengan bonus_umum
        if ($deposit < $syarat_bonus_umum) {
            // $deposit_umum = DepositUmum::where('id_deposit_umum', 'DU01')->first();
            $bonus_deposit = 0;
            $sisa_deposit = $depositTableMember;
            $total_deposit = $bonus_deposit + $sisa_deposit + $deposit;
            $deposit_umum = DepositUmum::create([
                'id_deposit_umum' => $year.'.'.$month.'.'.$id_deposit_umum,
                'id_pegawai' => $request->id_pegawai,
                'id_member' => $request->id_member,
                'id_promo_umum' => 'Tidak Dapat Promo',
                'tanggal' => $request->tanggal,
                'deposit' => $deposit,
                'total_deposit' => $total_deposit,
                'bonus_deposit' => $bonus_deposit,
                'sisa_deposit' => $sisa_deposit,
            ]);
    
            // $member = $aktivasi_tahunan->member;
            $findMember->deposit = $total_deposit;
            $findMember->save();
            return response()->json(['message' => 'deposit lebih kecil daripada bonus umum.'], 200);
            $deposit_umum = DepositUmum::latest()->first();
            return new DepositUmumResource(true, 'Data Deposit Umum Berhasil Ditambahkan!', $deposit_umum);
        }else {
            // Jika bonus_deposit sama dengan bonus_umum, maka nilai pada tabel deposit_umum diupdate
            // $deposit_umum = DepositUmum::where('id_deposit_umum', 'DU01')->first();
            $bonus_deposit = $bonus_umum;
            $sisa_deposit = $depositTableMember;
            $total_deposit = $bonus_deposit + $sisa_deposit + $deposit;
            // $sisa_deposit = $member->$deposit;
            // $deposit_umum->save();
            $deposit_umum = DepositUmum::create([
                'id_deposit_umum' => $year.'.'.$month.'.'.$id_deposit_umum,
                'id_pegawai' => $request->id_pegawai,
                'id_member' => $request->id_member,
                'id_promo_umum' => 'Dapat Promo',
                'tanggal' => $request->tanggal,
                'deposit' => $deposit,
                'total_deposit' => $total_deposit,
                'bonus_deposit' => $bonus_deposit,
                'sisa_deposit' => $sisa_deposit,
            ]);
            // $member->deposit = $total_deposit;
            $findMember->deposit = $total_deposit;
            $findMember->save();
            return response()->json(['message' => 'Bonus deposit diupdate menjadi sama dengan bonus umum.'], 200);
            // $member = $aktivasi_tahunan->member;
            $deposit_umum = DepositUmum::latest()->first();
            return new DepositUmumResource(true, 'Data Deposit Umum Berhasil Ditambahkan!', $deposit_umum);
        }   

        // $masa_membership = Carbon::now()->addYear();

        // $member = $request->aktivasi_tahunan;
        // $member->save();
        // $tanggalExpired = $request -> tanggal->addYear(1);

        // $tanggalNow = Carbon::parse($request->tanggal);
        // $tanggalExpired = $tanggalNow->addYear();

        //Fungsi Post ke Database
        //PEPEPEPEPEPEPE
        // $deposit_umum = DepositUmum::create([
        //     'id_deposit_umum' => $year.'.'.$month.'.'.$id_deposit_umum,
        //     'id_pegawai' => $request->id_pegawai,
        //     'id_member' => $request->id_member,
        //     'id_promo_umum' => $request->id_promo_umum,
        //     'tanggal' => $request->tanggal,
        //     'deposit' => $deposit,
        //     'total_deposit' => $request->total_deposit,
        //     'bonus_deposit' => $bonus_deposit,
        //     'sisa_deposit' => $request->sisa_deposit,
        // ]);

        // // $member = $aktivasi_tahunan->member;

        // $deposit_umum = DepositUmum::latest()->first();
        // return new DepositUmumResource(true, 'Data Deposit Umum Berhasil Ditambahkan!', $deposit_umum);
    }

    public function edit($id)
    {
        $deposit_umum = DepositUmum::findOrFail($id);
        return view('deposit_umum.edit', compact('deposit_umum'));
    }
    
    public function show($id_deposit_umum)
    {
        $deposit_umum = DepositUmum::find($id_deposit_umum);
        $member = Member::all();
        $pegawai = Pegawai::all();

        if(!is_null($deposit_umum)){
            return new DepositUmumResource(true, 'Data Ditemukan', $deposit_umum);
        }
        return new DepositUmumResource(true, 'Data Tidak Ditemukan', $deposit_umum);
    }

    public function update(Request $request, $id_deposit_umum)
    {
        $deposit_umum = DepositUmum::find($id_deposit_umum); 

        if(is_null($deposit_umum)){
            return response([
                'message' => 'Deposit Umum Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_deposit_umum' => 'required',
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'id_promo_umum' => 'required',
            'tanggal' => 'required',
            'total_deposit' => 'required',
            'bonus_deposit' => 'required',
            'sisa_deposit' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $deposit_umum->id_pegawai = $updateData['id_pegawai'];
        $deposit_umum->id_member = $updateData['id_member'];
        $deposit_umum->id_promo_umum = $updateData['id_promo_umum'];
        $deposit_umum->tanggal = $updateData['tanggal'];
        $deposit_umum->deposit = $updateData['deposit'];
        $deposit_umum->total_deposit = $updateData['total_deposit'];
        $deposit_umum->bonus_deposit = $updateData['bonus_deposit'];
        $deposit_umum->sisa_deposit = $updateData['sisa_deposit'];

        if($deposit_umum->save()){
            return response([
                'message' => 'Update Deposit Umum Success',
                'data' => $deposit_umum
            ], 200);
        }

        return response([
            'message' => 'Update Deposit Umum Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_deposit_umum)
    {
        $deposit_umum = DepositUmum::find($id_deposit_umum); 

        if(is_null($deposit_umum)){
            return response([
                'message' => 'Deposit Umum Not Found',
                'date' => null
            ], 404);
        } 

        if($deposit_umum->delete()){
            $deposit_umum->delete();
            return response([
                'message' => 'Deposit Umum Success',
                'data' => $deposit_umum
            ], 200);
        }

        return response([
            'message' => 'Delete Deposit Umum Failed',
            'data' => null, 
        ],400);
    }
}

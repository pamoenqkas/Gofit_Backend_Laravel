<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepositKelasResource;
use Illuminate\Http\Request;
use App\Models\DepositKelas;
use App\Models\PromoKelas;
use App\Models\Member;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DepositKelasController extends Controller
{
    public function index()
    {
        $deposit_kelas = DepositKelas::with('member', 'pegawai')->latest()->get();
        $member = Member::latest()->get();
        $pegawai = Pegawai::latest()->get();

        return new DepositKelasResource(
            true,
            'List Data Deposit Kelas',
            $deposit_kelas
        );
    }

    public function create()
    {
        return view('deposit_kelas.create');
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
            'id_deposit_kelas' => '',
            'id_pegawai' => 'required',
            'id_member' => 'required',
            // 'id_promo_kelas' => 'required',
            'tanggal' => '',
            'deposit_kelas' => 'required',
            'jenis_kelas' => 'required',
            // 'total_deposit' => 'required',
            // 'masa_berlaku' => 'required',
            // 'total_deposit' => 'required'
            // 'bonus_deposit' => 'required',
            // 'sisa_deposit' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // if ($validate->fails()) {
        //     return response()->json($validate->errors(), 422);
        // }

        //Generate Id
        $last = DepositKelas::latest()->first();
        if($last == null){
            $id_deposit_kelas = 1;
        }else{
            $id_deposit_kelas = ((int)Str::substr($last->id_deposit_kelas, 6,3)) + 1;
        }

        if($id_deposit_kelas < 10){
            $id_deposit_kelas = '00'.$id_deposit_kelas;
        }else if($id_deposit_kelas <100){
            $id_deposit_kelas = '0'.$id_deposit_kelas;
        }

        $curdate = Carbon::now()->format('Y-m-d');
        $month = Str::substr($curdate, 5, 2);
        $year = Str::substr($curdate, 2, 2);
        Str::substr($year, -2);

        //check bonus 
        $deposit_kelas_input = $request->input('deposit_kelas');
        $jenis_kelas = $request->input('jenis_kelas');
        //   return response([
        //     'data' => $deposit_kelas_input
        // ]);


        // Mendapatkan nilai bonus_kelas dari tabel promo_kelas
        //Promo Beli 5 gratis 1
        $promo_kelas_1 = PromoKelas::where('id_promo_kelas', 'PK01')->first();
        //   return response([
        //     'data' => $promo_kelas_1
        // ]);

        //Promo Beli 10 gratis 3
        $promo_kelas_2 = PromoKelas::where('id_promo_kelas', 'PK02')->first();
        // return response([
        //     'data' => $promo_kelas_2
        // ]);

        //Deklarasi Bonus Kelas (Tambah 1 dan Tambah 3)
        $bonus_kelas_1 = $promo_kelas_1->bonus_kelas;
        $bonus_kelas_2 = $promo_kelas_2->bonus_kelas;
        
        //Deklarasi Syarat Bonus kelas (Syarat 5 dan Syarat 10)
        $syarat_bonus_kelas_1 = $promo_kelas_1->syarat_bonus_kelas;
        $syarat_bonus_kelas_2 = $promo_kelas_2->syarat_bonus_kelas;
        // $bonus_deposit_kelas_1 = $promo_kelas_1->bonus_deposit;
        // $bonus_deposit_kelas_2 = $promo_kelas_2->bonus_deposit;

        // $member = Member::where('id_member', $id_member)->first();

        // $member = Member::findOrFail($id_member);
        $member = $request->id_member;
        $findMember = Member::find($member);
        // $depositTableMember = $member->deposit_kelas;
        // $sisa_deposit = $member->sisa_deposit;
        // return response([
        //     'data' => $member[0]->$deposit,
        // ]);
        // $member = $request->input('id_member');
        // $sisa_deposit = $member->$deposit;

        $tanggalNow = Carbon::parse($request->tanggal);
        $tanggalExpired = $tanggalNow->addYear();

        // $member = Member::findOrFail($id_member);

        // Memeriksa apakah input deposit_kelas = promo kelas PK01 (beli 5 gratis 1)
        if ($deposit_kelas_input == $syarat_bonus_kelas_1) {
            // $deposit_kelas = Depositkelas::where('id_deposit_kelas', 'DU01')->first();

            // $bonus_deposit_kelas = $bonus_kelas_1;

            // $sisa_deposit = $depositTableMember;
            // $promo_kelas_1 = PromoKelas::where('id_promo_kelas', 'PK01')->first();
            if($jenis_kelas = 'Spine Corrector' || $jenis_kelas = 'Muay Thai' || $jenis_kelas = 'Pilates' || $jenis_kelas = 'Asthanga' ||
                $jenis_kelas = 'Body Combat' || $jenis_kelas = 'Zumba' || $jenis_kelas = 'Wall Swing' || $jenis_kelas = 'Basic Swing' || 
                $jenis_kelas = 'Bellydance' || $jenis_kelas = 'Yogalates' || $jenis_kelas = 'Boxing' || $jenis_kelas = 'Calisthenics' ||
                $jenis_kelas = 'Pound Fit' || $jenis_kelas = 'Yoga For Kids' || $jenis_kelas = 'Abs Pilates' || $jenis_kelas = 'Swing For Kids')
            {
                //benar
                $deposit_kelas_input = $deposit_kelas_input + $bonus_kelas_1;
                $jenis_kelas = $request->input('jenis_kelas');
                //deposit_kelas = 6 
                $total_deposit = 0;
                $total_deposit = ($deposit_kelas_input - $bonus_kelas_1) * 150000;
            }else if($jenis_kelas = 'Bunggee' || $jenis_kelas = 'Trampoline Workout'){
                $deposit_kelas_input = $deposit_kelas_input + $bonus_kelas_1;
                $jenis_kelas = $request->input('jenis_kelas');
                $total_deposit = 0;
                $total_deposit = ($deposit_kelas_input - $bonus_kelas_1) * 200000;
            }else{}
            // $total_deposit = $bonus_kelas_1 + $deposit_kelas;
            $deposit_kelas = DepositKelas::create([
                'id_deposit_kelas' => $year.'.'.$month.'.'.$id_deposit_kelas,
                'id_pegawai' => $request->id_pegawai,
                'id_member' => $request->id_member,
                'id_promo_kelas' => $promo_kelas_1->id_promo_kelas,
                'tanggal' => Carbon::now()->toDateString(),
                'deposit_kelas' => $deposit_kelas_input,
                'jenis_kelas' => $jenis_kelas,
                'total_deposit' => $total_deposit,
                'masa_berlaku' => $tanggalExpired,
            ]);

            // $member = $aktivasi_tahunan->member;
            $findMember->deposit_kelas = $deposit_kelas_input + $findMember->deposit_kelas;
            $findMember->masa_berlaku_kelas = $tanggalExpired;
            $findMember->save();            
            return response()->json(['message' => 'Deposit Kelas Sebanyak 5 Mendapat 1 Bonus Kelas'], 200);
            $deposit_kelas = DepositKelas::latest()->first();
            return new DepositKelasResource(true, 'Data Deposit Kelas Berhasil Ditambahkan!', $deposit_kelas);
        }else if ($deposit_kelas_input == $syarat_bonus_kelas_2){
            // Jika bonus_deposit sama dengan bonus_kelas, maka nilai pada tabel deposit_kelas diupdate
            // $deposit_kelas = Depositkelas::where('id_deposit_kelas', 'DU01')->first();
            // $sisa_deposit = $member->$deposit;
            // $deposit_kelas->save();

            // $promo_kelas_2 = PromoKelas::where('id_promo_kelas', 'PK02')->first();

            if($jenis_kelas = 'Spine Corrector' || $jenis_kelas = 'Muay Thai' || $jenis_kelas = 'Pilates' || $jenis_kelas = 'Asthanga' ||
                $jenis_kelas = 'Body Combat' || $jenis_kelas = 'Zumba' || $jenis_kelas = 'Wall Swing' || $jenis_kelas = 'Basic Swing' || 
                $jenis_kelas = 'Bellydance' || $jenis_kelas = 'Yogalates' || $jenis_kelas = 'Boxing' || $jenis_kelas = 'Calisthenics' ||
                $jenis_kelas = 'Pound Fit' || $jenis_kelas = 'Yoga For Kids' || $jenis_kelas = 'Abs Pilates' || $jenis_kelas = 'Swing For Kids')
            {
                // $total_deposit = $deposit_kelas_input * 150000;
                // $deposit_kelas_input = $deposit_kelas_input;
                // $jenis_kelas = $request->input('jenis_kelas');
                  //benar
                  $deposit_kelas_input = $deposit_kelas_input + $bonus_kelas_2;
                  $jenis_kelas = $request->input('jenis_kelas');
                  //deposit_kelas = 6 
                  $total_deposit = 0;
                  $total_deposit = ($deposit_kelas_input - $bonus_kelas_2) * 150000;
            }else if($jenis_kelas = 'Bunggee' || $jenis_kelas = 'Trampoline Workout'){
                // $total_deposit = $deposit_kelas_input * 200000;
                // $deposit_kelas_input = $deposit_kelas_input;
                // $jenis_kelas = $request->input('jenis_kelas');
                $deposit_kelas_input = $deposit_kelas_input + $bonus_kelas_2;
                $jenis_kelas = $request->input('jenis_kelas');
                $total_deposit = 0;
                $total_deposit = ($deposit_kelas_input - $bonus_kelas_2) * 200000;
            }else{}
            // $total_deposit = $bonus_kelas_2 + $deposit_kelas;
            $deposit_kelas = DepositKelas::create([
                'id_deposit_kelas' => $year.'.'.$month.'.'.$id_deposit_kelas,
                'id_pegawai' => $request->id_pegawai,
                'id_member' => $request->id_member,
                'id_promo_kelas' => $promo_kelas_2->id_promo_kelas,
                'tanggal' => Carbon::now()->toDateString(),
                'deposit_kelas' => $deposit_kelas_input,
                'jenis_kelas' => $jenis_kelas,
                'total_deposit' => $total_deposit,
                'masa_berlaku' => $tanggalExpired,
            ]);
            // $member->deposit = $total_deposit;
            // $member = $aktivasi_tahunan->member;
            $findMember->deposit_kelas = $deposit_kelas_input + $findMember->deposit_kelas;
            $findMember->masa_berlaku_kelas = $tanggalExpired;
            $findMember->save();            
            return response()->json(['message' => 'Deposit Kelas Sebanyak 10 Mendapat 3 Bonus Kelas'], 200);
            $deposit_kelas = DepositKelas::latest()->first();
            return new DepositKelasResource(true, 'Data Deposit Kelas Berhasil Ditambahkan!', $deposit_kelas);
        }else{
            if($jenis_kelas = 'Spine Corrector' || $jenis_kelas = 'Muay Thai' || $jenis_kelas = 'Pilates' || $jenis_kelas = 'Asthanga' ||
            $jenis_kelas = 'Body Combat' || $jenis_kelas = 'Zumba' || $jenis_kelas = 'Wall Swing' || $jenis_kelas = 'Basic Swing' || 
            $jenis_kelas = 'Bellydance' || $jenis_kelas = 'Yogalates' || $jenis_kelas = 'Boxing' || $jenis_kelas = 'Calisthenics' ||
            $jenis_kelas = 'Pound Fit' || $jenis_kelas = 'Yoga For Kids' || $jenis_kelas = 'Abs Pilates' || $jenis_kelas = 'Swing For Kids')
        {
            // $total_deposit = $deposit_kelas_input * 150000;
            // $deposit_kelas_input = $deposit_kelas_input;
            // $jenis_kelas = $request->input('jenis_kelas');
              //benar
              $deposit_kelas_input = $deposit_kelas_input;
              $jenis_kelas = $request->input('jenis_kelas');
              //deposit_kelas = 6 
              $total_deposit = $deposit_kelas_input * 150000;
        }else if($jenis_kelas = 'Bunggee' || $jenis_kelas = 'Trampoline Workout'){
            // $total_deposit = $deposit_kelas_input * 200000;
            // $deposit_kelas_input = $deposit_kelas_input;
            // $jenis_kelas = $request->input('jenis_kelas');
            $deposit_kelas_input = $deposit_kelas_input;
            $jenis_kelas = $request->input('jenis_kelas');
            $total_deposit = $deposit_kelas_input * 200000;
        }else{}
            $deposit_kelas = DepositKelas::create([
                'id_deposit_kelas' => $year.'.'.$month.'.'.$id_deposit_kelas,
                'id_pegawai' => $request->id_pegawai,
                'id_member' => $request->id_member,
                'id_promo_kelas' => 'Tidak Dapat Promo',
                'tanggal' => Carbon::now()->toDateString(),
                'deposit_kelas' => $deposit_kelas_input,
                'jenis_kelas' => $request->jenis_kelas,
                'total_deposit' => $total_deposit,
                'masa_berlaku' => $tanggalExpired,
            ]);
            return response([
                'data' => $tanggalExpired
            ]);
            $findMember->deposit_kelas = $deposit_kelas_input + $findMember->deposit_kelas;
            $findMember->masa_berlaku_kelas = $tanggalExpired;
            $findMember->save();            
            // return response()->json(['message' => 'Deposit Kelas Sebanyak 10 Mendapat 3 Bonus Kelas'], 200);
            $deposit_kelas = DepositKelas::latest()->first();
            return new DepositKelasResource(true, 'Data Deposit Kelas Berhasil Ditambahkan!', $deposit_kelas);
        }

        // $masa_membership = Carbon::now()->addYear();

        // $member = $request->aktivasi_tahunan;
        // $member->save();
        // $tanggalExpired = $request -> tanggal->addYear(1);

        // $tanggalNow = Carbon::parse($request->tanggal);
        // $tanggalExpired = $tanggalNow->addYear();

        //Fungsi Post ke Database
        //PEPEPEPEPEPEPE
        // $deposit_kelas = DepositKelas::create([
        //     'id_deposit_kelas' => $year.'.'.$month.'.'.$id_deposit_kelas,
        //     'id_pegawai' => $request->id_pegawai,
        //     'id_member' => $request->id_member,
        //     'id_promo_kelas' => $request->id_promo_kelas,
        //     'tanggal' => $request->tanggal,
        //     'deposit' => $deposit,
        //     'total_deposit' => $request->total_deposit,
        //     'bonus_deposit' => $bonus_deposit,
        //     'sisa_deposit' => $request->sisa_deposit,
        // ]);

        // // $member = $aktivasi_tahunan->member;

        // $deposit_kelas = DepositKelas::latest()->first();
        // return new DepositKelasmResource(true, 'Data Deposit Kelas Berhasil Ditambahkan!', $deposit_kelas);
    }

    public function edit($id)
    {
        $deposit_kelas = DepositKelas::findOrFail($id);
        return view('deposit_kelas.edit', compact('deposit_kelas'));
    }
    
    public function show($id_deposit_kelas)
    {
        $deposit_kelas = DepositKelas::find($id_deposit_kelas);
        $member = Member::all();
        $pegawai = Pegawai::all();


        if(!is_null($deposit_kelas)){
            return new DepositKelasResource(true, 'Data Ditemukan', $deposit_kelas);
        }
        return new DepositKelasResource(true, 'Data Tidak Ditemukan', $deposit_kelas);
    }

    public function update(Request $request, $id_deposit_kelas)
    {
        $deposit_kelas = DepositKelas::find($id_deposit_kelas); 

        if(is_null($deposit_kelas)){
            return response([
                'message' => 'Deposit kelas Not Found',
                'data' => null
            ], 404);
        } 

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_deposit_kelas' => 'required',
            'id_pegawai' => 'required',
            'id_member' => 'required',
            'id_promo_kelas' => 'required',
            'tanggal' => 'required',
            'deposit_kelas' => 'required',
            'jenis_kelas' => 'required',
            'total_deposit' => 'required',
            'masa_berlaku' => 'required',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); 
        }
        $deposit_kelas->id_pegawai = $updateData['id_pegawai'];
        $deposit_kelas->id_member = $updateData['id_member'];
        $deposit_kelas->id_promo_kelas = $updateData['id_promo_kelas'];
        $deposit_kelas->tanggal = $updateData['tanggal'];
        $deposit_kelas->deposit_kelas = $updateData['deposit_kelas'];
        $deposit_kelas->jenis_kelas = $updateData['jenis_kelas'];
        $deposit_kelas->total_deposit = $updateData['total_deposit'];
        $deposit_kelas->masa_berlaku = $updateData['masa_berlaku'];

        if($deposit_kelas->save()){
            return response([
                'message' => 'Update Deposit kelas Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Update Deposit kelas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id_deposit_kelas)
    {
        $deposit_kelas = DepositKelas::find($id_deposit_kelas); 

        if(is_null($deposit_kelas)){
            return response([
                'message' => 'Deposit kelas Not Found',
                'date' => null
            ], 404);
        } 

        if($deposit_kelas->delete()){
            $deposit_kelas->delete();
            return response([
                'message' => 'Deposit kelas Success',
                'data' => $deposit_kelas
            ], 200);
        }

        return response([
            'message' => 'Delete Deposit kelas Failed',
            'data' => null, 
        ],400);
    }

    public function expiredToday(){
        $deposit_kelas = DepositKelas::all(); 

        foreach ($deposit_kelas as $data) {
            $today = Carbon::now()->toDateString();
            // $bulanDepan = Carbon::now()->startOfMonth();
            $deposit_kelas = DepositKelas::whereDate('masa_berlaku', $today)->get();
            $masaBerlaku = $data->masa_berlaku;

            if ($masaBerlaku == $today) {
                return response([
                    'message' => 'Deposit Kelas Sudah Kedaluarsa per Hari Ini',
                    'data' => $deposit_kelas
                ], 200);
            }else{
                return response([
                    'message' => 'Tidak Ada Deposit Kelas yang Kedaluarsa per Hari Ini ',
                    'data' => null
                ], 200);
            }
        }
    }

    public function resetExpiredToday(){
        $deposit_kelas_find = DepositKelas::all(); 

        foreach ($deposit_kelas_find as $data) {
            $today = Carbon::now()->toDateString();
            // $deposit_kelas_date = DepositKelas::whereDate('masa_berlaku', $today)->get();
            $masaBerlaku = $data->masa_berlaku;

            if ($masaBerlaku == $today) {
                $data->total_deposit = 0;
                $data->deposit_kelas = 0;
                $data->masa_berlaku = null;
                $data->save();
            }else{}
        }
        return response([
            'message' => 'Berhasil Melakukan Reset Deposit Kelas Member',
            'data' => $deposit_kelas_find
        ], 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Member;
use App\Models\Pegawai;
use App\Models\Instruktur;
use App\Models\Role;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function login(Request $request){
        // $loginData = $request->all();
        $validate = Validator::make($request->all(), [
            'email' => 'required',
            'password' =>'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 422);
        }

        if($member = Member::where('email_member', $request->email)->first()){
            $loginMember = Member::where('email_member', '=', $request->email)->first();

            if(Hash::check($request['password'], $member->password)){
                $member = Member::where('email_member', '=',$request->email_member)->first();
            }else{
                return response(['message' => 'Invalid Password or Email'], 404);
            }

            $token = bcrypt(uniqid());
            // $token = $member->createToken('Authentication Token')->accessToken;
            return response([
                'message' => 'Successfully Login as member',
                'id' => $loginMember->id_member, 
                'token'=> $token,
                'user' => 'Member',
                'member' => $loginMember,
                'token'=> $token,
            ], 200);
        }else if($pegawai = Pegawai::where('email_pegawai', $request->email)->first()){
            $loginPegawai = Pegawai::where('email_pegawai', '=', $request->email)->first();

            if(Hash::check($request->password, $pegawai->password)){
                $pegawai = Pegawai::where('email_pegawai', '=',$request->email_pegawai)->first();
            }else{
                return response(['message' => 'Invalid Password or Email'], 404);
            }

            // $role = Role::find($loginPegawai['id_role']);

            // $token = bcrypt(uniqid());
            // $token = $member->createToken('Authentication Token')->accessToken;
            return response([
                'message' => 'Successfully Login as pegawai',
                'success' => true,
                'role' => $loginPegawai->id_role, // Mengambil nama peran dari model Role
                'id' => $loginPegawai->id_pegawai, 
                // 'token'=> $token
            ], 200);
        }else if($instruktur = instruktur::where('email_instruktur',$request->email)->first()){
            $loginInstruktur = instruktur::where('email_instruktur', '=',$request->email)->first();

            if(Hash::check($request->password, $instruktur->password)){
                $instruktur = instruktur::where('email_instruktur', '=',$request->email_instruktur)->first();
            }else{
                return response(['message' => 'Invalid Password or Email'], 404);
            }

            $token = bcrypt(uniqid());
            // $token = $member->createToken('Authentication Token')->accessToken;
            return response([
                'message' => 'Successfully Login as instruktur',
                'id' => $loginInstruktur->id_instruktur, 
                'token'=> $token,
                'user' => 'Instruktur'
            ], 200);
        }else{
            return response([
                'message' => 'Gagal login',
                'data' => null, 
            ], 401);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'success' => true,
            'message' => 'Success Logout',
        ], 200);
    }
}
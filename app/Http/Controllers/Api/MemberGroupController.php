<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\MemberGroups;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberGroupController extends Controller
{
     //update data pada User
     public function DaftarUlang(Request $request){
        $user = MemberGroups::where('member_id_groups',$request->member_id_groups)->where('user_email',$request->user_email)->first();

        if(is_null($user)){
            return response([
                'message' => 'Member ID dan Email yang terdapat pada database kami tidak sesuai! silahkan coba ulang kembali',
                'data' => null
            ], 404);
        }

        if($user->is_verified != false){
            return response([
                'message' => 'Akun ini sudah terdaftar',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'member_id_groups' =>  'required',
            'user_fullname' =>  'required',
            'user_email' =>  'required|email:rfc,dns',
            'user_password' =>  'required',
            'user_gender' =>  'required',
            'user_telephone' =>  'required',
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $user->user_fullname = $updateData['user_fullname'];
        $user->user_password = bcrypt($user['user_password']);
        $user->user_gender = $updateData['user_gender'];
        $user->user_telephone = $updateData['user_telephone'];   
        $user->is_verified = true;   

        if($user->save()){
            return response([
                'message' => 'Daftar Ulang Berhasil',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Berhasil',
            'data' => null
        ], 400);
    }

    public function updateProfilePictureUser(Request $request, $member_id_groups){
        $user = MemberGroups::where('member_id_groups', $member_id_groups)->first();

        if(is_null($user)){
            return response([
                'message' => 'User tidak ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'user_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'  
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        if(isset($request->user_picture)){
            $uploadPictureUser = $request->user_picture->store('img_user', ['disk' => 'public']);
            $user->user_picture = $uploadPictureUser;
        }


        if($user->save()){
            return response([
                'message' => 'Update Gambar Berhasil!',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Update Gambar Gagal!',
            'data' => null
        ], 400);
    }

    public function updateMemberUser(Request $request, $member_id_groups){
        $user = MemberGroups::where('member_id_groups', $member_id_groups)->first();

        if(is_null($user)){
            return response([
                'message' => 'User Tidak Ditemukan',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'member_id' => 'nullable', 
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $user->member_id = $updateData['member_id'];

        if($user->save()){
            return response([
                'message' => 'Memilih Member JKT48 Berhasil',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'Memilih Member JKT48 Gagal',
            'data' => null
        ], 400);
    }

    public function show($member_id_groups){
        $user = MemberGroups::with(['Member'])->where('member_id_groups', $member_id_groups)->first();

        if(!is_null($user)){
            return response([
                'message' => 'Retrieve User Success',
                'data' => $user
            ], 200);
        }

        return response([
            'message' => 'User Not Found',
            'data' => null
        ], 400);
    }

    public function updatePassword(Request $request, $member_id_groups){
        $data = MemberGroups::where('member_id_groups', $member_id_groups)->first();

        $storeData = $request->all();
        $validator = Validator::make($storeData, [
            'password' => 'required',
            'newPassword' => 'required',
            'confirmNewPassword' => 'required',
        ]);

        $checkedPass = Hash::check($request->password, $data->user_password);
        
        if(!$checkedPass){
            return response([
                'message' => 'Password Salah!',
                'data' => null
            ], 400);
        }
        

        $userData['user_password'] = Hash::make($request->newPassword);
        $data->update($userData);

        if($validator->fails()){
            // return response([
            //     'message' => 'Ganti Password Gagaal',
            // ], 400);
            return response(['message' => $validate->errors()], 400);
        }

        return response([
            'message' => 'Ganti Password Sukses',
            'data' => $data,
        ], 200);
    }

    public function login(Request $request)
    {
        $loginData = $request->all();
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required'
        ]); 

        if(MemberGroups::where('user_email',$loginData['email'])->first()){
            $loginUser = MemberGroups::where('user_email', $loginData['email'])->first();

            if($loginUser->is_verified == false){
                return response([
                    'message' => 'Akun ini belum melakukan daftar ulang',
                    'data' => null
                ], 404);
            }

            $checkHashedPass = MemberGroups::where('user_email', $request->email)->first();
            $checkedPass = Hash::check($request->password, $checkHashedPass->user_password);

            if($checkedPass){
                $data = MemberGroups::where('user_email', $request->email)->first();
                return response([
                    'message' => 'Login Sukses',
                    'data' => $data
                ]);
            }
        }
      
        return response([
            'message' => 'Login Gagal, Cek Email dan Password Kembali',
            'data' => null
        ], 404);
    

        if ($validate->fails())
            return response(['message' => $validate->error()], 400); //return error validasi input
    }



}

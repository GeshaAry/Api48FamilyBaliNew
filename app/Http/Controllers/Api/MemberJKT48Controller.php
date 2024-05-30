<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Memberjkt48;
use Illuminate\Support\Facades\DB;

class MemberJKT48Controller extends Controller
{
    //mereturnkan semua data yang ada pada member
    public function AllMember(){
        $member = Memberjkt48::all();

        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Member Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    //mereturnkan semua data member yang berulang tahun
    public function BirthdayMember(){
        $month = date("m");
        $members = Memberjkt48::whereMonth("member_birthdate", $month)->get();

        return $members;
  
        if(count($members) > 0){
            return response([
                'message' => 'Member Birthday',
                'data' => $members
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }
    
    //mereturnkan semua data yang ada pada member
    public function index(Request $request){
        $limit = $request->query('limit') ?? 100;
        $member = Memberjkt48::paginate($limit);

        if(count($member) > 0){
            return response([
                'message' => 'Retrieve All Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada member
    public function show($member_id){
        $member = Memberjkt48::where('member_id', $member_id)->first();

        if(!is_null($member)){
            return response([
                'message' => 'Retrieve Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Member JKT48 Not Found',
            'data' => null
        ], 400);
    }

    //menambah data pada admin
    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'member_name' => 'required',
            'member_jiko' =>  'required',
            'member_gen' =>  'required',
            'member_status' =>  'required',
            'member_birthdate' =>  'required',
            'member_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'            
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }


        if(!empty($request->member_picture)){
            $uploadPictureMember = $request->member_picture->store('img_member', ['disk' => 'public']);
        }
        else{
            $uploadPictureMember = NULL;
        }
        

        $member = Memberjkt48::create([
            'member_name' => $request->member_name,
            'member_jiko' => $request->member_jiko,
            'member_gen' => $request->member_gen,
            'member_status' => $request->member_status,
            'member_birthdate' => $request->member_birthdate,
            'member_picture' => $uploadPictureMember,
        ]);

        return response([
            'message' => 'Add Member JKT48 Success',
            'data' => $member
        ], 200);
    }


    //menghapus data pada member
    public function destroy($member_id){
        $member = Memberjkt48::where('member_id', $member_id);

        if(is_null($member)){
            return response([
                'message' => 'Member JKT48 Not Found',
                'date' => null
            ], 404);
        }

        if($member->delete()){
            return response([
                'message' => 'Delete Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Delete Member JKT48 Failed',
            'data' => null,
        ], 400);
    }

    //update data pada member
    public function update(Request $request, $member_id){
        $member = Memberjkt48::where('member_id', $member_id)->first();

        if(is_null($member)){
            return response([
                'message' => 'Member JKT48 Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();

        $validate = Validator::make($updateData, [
            'member_name' => 'required',
            'member_jiko' =>  'required',
            'member_gen' =>  'required',
            'member_status' =>  'required',
            'member_birthdate' =>  'required',
            'member_picture' =>  'nullable|max:1024|mimes:jpg,png,jpeg|image'     
        ]);

        
        if($validate->fails()){
            return response(['message' => $validate->errors()], 400);
        }

        $member->member_name = $updateData['member_name'];
        $member->member_jiko = $updateData['member_jiko'];
        $member->member_gen = $updateData['member_gen'];
        $member->member_status = $updateData['member_status'];
        $member->member_birthdate = $updateData['member_birthdate'];
        if(isset($request->member_picture)){
            $uploadPictureMember = $request->member_picture->store('img_member', ['disk' => 'public']);
            $member->member_picture = $uploadPictureMember;
        }


        if($member->save()){
            return response([
                'message' => 'Update Member JKT48 Success',
                'data' => $member
            ], 200);
        }

        return response([
            'message' => 'Update Member JKT48 Failed',
            'data' => null
        ], 400);
    }
}

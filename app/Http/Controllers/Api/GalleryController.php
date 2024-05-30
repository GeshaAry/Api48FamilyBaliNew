<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;

class GalleryController extends Controller
{
    //mereturnkan semua data yang ada pada gallery
    public function index(){
        $gallery = Gallery::with(['Admin'])->get();

        if(count($gallery) > 0){
            return response([
                'message' => 'Retrieve All Gallery Success',
                'data' => $gallery
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    
    //mereturnkan data yang dipilih pada gallery
    public function show($gallery_id){
        $gallery = Gallery::with(['Admin'])->where('gallery_id', $gallery_id)->first();

        if(!is_null($gallery)){
            return response([
                'message' => 'Retrieve Picture Success',
                'data' => $gallery
            ], 200);
        }

        return response([
            'message' => 'Gambar Tidak Ditemukan',
            'data' => null
        ], 400);
    }

    //menambah data pada gallery
    public function store(Request $request){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'admin_id' => 'required',
            'gallery_picture' => 'nullable|max:1024|mimes:jpg,png,jpeg|image',
        ]);

        if($validate->fails()){
            return response(['message' => $validate->errors()], 400); //Return error invalid input
        }

        $uploadPictureGallery = $request->gallery_picture->store('img_gallery', ['disk' => 'public']);

        $gallery = Gallery::create([
            'admin_id' => $request->admin_id,
            'gallery_picture' => $uploadPictureGallery,
        ]);

        return response([
            'message' => 'Tambah Gambar Sukses',
            'data' => $gallery
        ], 200);
    }


    //menghapus data pada gallery
    public function destroy($gallery_id){
        $gallery = Gallery::where('gallery_id', $gallery_id);

        if(is_null($gallery)){
            return response([
                'message' => 'Gambar Tidak Ditemukan',
                'date' => null
            ], 404);
        }

        if($gallery->delete()){
            return response([
                'message' => 'Gambar Berhasil Dihapus',
                'data' => $gallery
            ], 200);
        }

        return response([
            'message' => 'Gambar Gagal Dihapus',
            'data' => null,
        ], 400);
    }

    //update data pada gallery
    // public function update(Request $request, $gallery_id){
    //     $gallery = Gallery::where('gallery_id', $gallery_id)->first();

    //     if(is_null($gallery)){
    //         return response([
    //             'message' => 'Picture Not Found',
    //             'data' => null
    //         ], 404);
    //     }

    //     $updateData = $request->all();

    //     $validate = Validator::make($updateData, [
    //         'gallery_picture' => 'nullable|max:1024|mimes:jpg,png,jpeg|image',
    //     ]);

        
    //     if($validate->fails()){
    //         return response(['message' => $validate->errors()], 400);
    //     }

    //     if(isset($request->gallery_picture)){
    //         $uploadPictureGallery = $request->gallery_picture->store('img_gallery', ['disk' => 'public']);
    //         $gallery->gallery_picture = $uploadPictureGallery;
    //     }

    //     if($gallery->save()){
    //         return response([
    //             'message' => 'Update Picture Success',
    //             'data' => $gallery
    //         ], 200);
    //     }

    //     return response([
    //         'message' => 'Update Picture Failed',
    //         'data' => null
    //     ], 400);
    // }
}

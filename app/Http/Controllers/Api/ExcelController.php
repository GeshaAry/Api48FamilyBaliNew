<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MemberGroupsImport;

class ExcelController extends Controller
{
    public function importMemberGroupsDoc(Request $request){
        if (is_null($request->file('csv'))) {
            return response("Impor Gagal, File Tidak Tersedia!", 400);
        }

        $directory = "import/";

        $filename = $request->file('csv')->getClientOriginalName();
        $request->file('csv')->storeAs($directory, $filename);
        $filePath = $directory . $filename;
        // dd($filePath);

        Excel::import(new MemberGroupsImport, $filePath);
    }
}

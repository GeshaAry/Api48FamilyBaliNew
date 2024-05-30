<?php

namespace App\Imports;

use App\Models\MemberGroups;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MemberGroupsImport implements ToModel, SkipsEmptyRows, WithHeadingRow, WithValidation
{

    use Importable;
    
    public function rules(): array
    {
        return [
            'member_id_groups' => [
                'required',
            ],
            'user_fullname' => [
                'required',
            ],
            'user_email' => [
                'required',
            ],
        ];
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // return new MemberGroups([
        //     'member_id_groups' => $row['member_id_groups'],
        //     'user_fullname' => $row['user_fullname'],
        //     'user_email' => $row['user_email'],
        // ]);

        $insertdata=[
            'member_id_groups' => $row['member_id_groups'],
            'user_fullname' => $row['user_fullname'],
            'user_email' => $row['user_email'],
        ];

        $membergroup=MemberGroups::create($insertdata);

        return $membergroup;


    }
}

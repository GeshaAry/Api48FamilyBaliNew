<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MemberGroups extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_id';
    // protected $fillable = [
    //     'user_id',
    //     'member_id',
    //     'member_id_groups',
    //     'user_fullname',
    //     'user_email',
    //     'user_password',
    //     'user_gender',
    //     'user_telephone',
    //     'user_picture',
    //     'is_verified'
    // ];

    protected $guarded = [
        "user_id",
        "created_at",
        "updated_at",
    ];

    public function getCreatedAtAttribute(){
        if(!is_null($this->attributes['created_at'])){
            return Carbon::parse($this->attributes['created_at'])->format('Y-m-d H:i:s');
        }
    }

    public function getUpdatedAtAttribute(){
        if(!is_null($this->attributes['updated_at'])){
            return Carbon::parse($this->attributes['updated_at'])->format('Y-m-d H:i:s');
        }
    }
    
    public function Member(){
        return $this->belongsTo(Memberjkt48::class, 'member_id', 'member_id');
    }
}

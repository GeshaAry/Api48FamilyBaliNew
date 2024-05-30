<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Gallery extends Model
{
    use HasFactory;
    protected $primaryKey = 'gallery_id';
    protected $fillable = [
        'gallery_id',
        'admin_id',
        'gallery_picture'
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

    public function Admin(){
        return $this->belongsTo(Admin::class, 'admin_id', 'admin_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function userSharing(){
        return $this->belongsTo(User::class,'user_id_1','id');
    }

    public function userSharedWith(){
        return $this->belongsTo(User::class,'user_id_2','id');
    }

    public function file(){
        return $this->belongsTo(File::class,'file_id','id');
    }

    public function folder(){
        return $this->belongsTo(Folder::class,'folder_id','id');
    }
}

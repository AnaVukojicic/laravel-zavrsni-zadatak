<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function folder(){
        return $this->belongsTo(Folder::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function shares(){
        return $this->hasMany(Share::class,'file_id','id');
    }

    public function getUsersSharedWith(){
        $sharedWith=[];
        foreach($this->shares as $share){
            $sharedWith[]=$share->userSharedWith;
        }
        return $sharedWith;
    }

    public function getUsersToShare(){
        $sharedWith=[];
        foreach($this->shares as $share){
            $sharedWith[]=$share->user_id_2;
        }
        foreach($this->shares as $share){
            $sharedWith[]=$share->user_id_2;
        }
        $restOfUsers=User::query()->whereNotIn('id',$sharedWith)->where('id','!=',auth()->id())->get();
        return $restOfUsers;
    }

    public function getSizeInKbAttribute(){
        return number_format(($this->size)/1024,2)." KB";
    }

}

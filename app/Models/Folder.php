<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function folder(){
        return $this->belongsTo(Folder::class);
    }

    public function folders(){
        return $this->hasMany(Folder::class);
    }

    public function files(){
        return $this->hasMany(File::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function getFolderSizeAttribute(){
        $size=0;
        $files=$this->files;
        foreach ($files as $file){
            $size+=$file->size;
        }
        $subfolders=$this->subfolders();
        if($subfolders){
            foreach($subfolders as $sub){
                $size+=$sub->folder_size;
            }
        }
        return $size;
    }

    public function getSizeOfFolderInKbAttribute(){
        $sizeOfFolder=$this->folder_size;
        return number_format($sizeOfFolder/1024,2)." KB";
    }

    public function subfolders(){
        return $this->folders()->with(['files','folders'])->where('user_id',auth()->id())->get();
    }

    public function subfiles(){
        return $this->files()->where('user_id',auth()->id())->get();
    }

    public function shares(){
        return $this->hasMany(Share::class,'folder_id','id');
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

    public function getSharedSubfiles(){
        $folders=$this->folders()->with(['files','folders'])->get();
        $files=$this->files()->get();
        return ['folders'=>$folders,'files'=>$files];
    }

}

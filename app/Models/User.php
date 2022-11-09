<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function files(){
        return $this->hasMany(File::class);
    }

    public function folders(){
        return $this->hasMany(Folder::class);
    }

    public function extensions(){
        return $this->hasMany(Extension::class);
    }

    public function doesFileExists($users_name,$folder_id,$extension){
        return $this->files()->where('folder_id',$folder_id)
                ->where('users_name',$users_name)
                ->where('extension',$extension)->count()>0;
    }

    public function doesFolderExists($users_name,$folder_id){
        return $this->folders()->where('folder_id',$folder_id)
                ->where('users_name',$users_name)->count()>0;
    }

    public function doesUserHasExtension($name){
        return $this->extensions()->where('name',$name)->count()>0;
    }

    public function getRootFiles(){
        return $this->files()->whereNull('folder_id')->get();
    }
    public function getRootFolders(){
        return $this->folders()->with(['files','folders'])->whereNull('folder_id')->get();
    }

    public function usedStorage(){
        $files=$this->files;
        $used_space=0;
        foreach($files as $f){
            $used_space+=$f->size;
        }
        return $used_space;
    }

    public function getUsedStoragePercentageAttribute(){
        $usedStorageToGB=$this->usedStorage()/1024/1024/1024;
        return $usedStorageToGB*100*1;
    }

    public function getUsedStorageInMbAttribute(){
        return $this->usedStorage()/1024/1024;
    }

    public function sharesCreated(){
        return $this->hasMany(Share::class,'user_id','id');
    }

    public function sharesReceived(){
        return $this->hasMany(Share::class,'user_id_2','id');
    }

    public function usersSharedWith(){
        return $this->belongsToMany(User::class,'shares','user_id_1','user_id_2');
    }

    public function usersWhoShare(){
        return $this->belongsToMany(User::class,'shares','user_id_2','user_id_1');
    }

    public function get3FilesSharedWithUser(){
        $shares=$this->sharesReceived()->with(['file','folder'])->orderBy('created_at','desc')->limit(3)->get();
        $sharedFiles=[];
        foreach ($shares as $share){
            $share->file ? $sharedFiles[]=$share->file : $sharedFiles[]=$share->folder;
        }
        return $sharedFiles;
    }
    public function getAllFilesSharedWithUser(){
        $shares=$this->sharesReceived()->with(['file','folder'])->orderBy('created_at','desc')->get();
        $sharedFiles=[];
        foreach ($shares as $share){
            $share->file ? $sharedFiles[]=$share->file : $sharedFiles[]=$share->folder;
        }
        return $sharedFiles;
    }

    public function availableSTorage(){
        $used=$this->usedStorage();
        $total=$this->max_storage_gb*1024*1024*1024;
        return $total-$used;
    }

}

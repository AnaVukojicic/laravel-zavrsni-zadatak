<?php

namespace App\Http\Controllers;

use App\Mail\SharedFileMail;
use App\Mail\SharedFolderMail;
use App\Models\File;
use App\Models\Folder;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Requests\UpdateFolderRequest;
use App\Models\Share;
use App\Models\User;
use App\Notifications\SharedFileNotification;
use App\Notifications\SharedFolderNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function createInExisting(Folder $folder){
        return view('files-folders.create-in-existing',['folder'=>$folder]);
    }

    public function storeHelper($request,$folder_id,$user_id){
        $folder_name=$request->folder_name==null ? $request->folder_name_uploaded : $request->folder_name;
        if(!Storage::exists('folders')){
            Storage::makeDirectory('folders');
        }

        if(!auth()->user()->doesFolderExists($folder_name,$folder_id)){
            $path='folders/'.uniqid(Str::replace(' ','_',$folder_name));
            Storage::makeDirectory($path);
            Folder::query()->create([
                'user_id'=>$user_id,
                'users_name' => $folder_name,
                'system_name' => $path,
                'folder_id' => $folder_id,
            ]);
            return redirect()->route('files.index');
        }else{
            return redirect()->route('add-new');
        }
    }

    public function store(StoreFolderRequest $request)
    {
        $user_id=auth()->id();
        $folder_id=null;
        return $this->storeHelper($request,$folder_id,$user_id);
    }

    public function storeToExisting(StoreFolderRequest $request,Folder $folder){
        $user_id=auth()->id();
        $folder_id=$folder->id;
        return $this->storeHelper($request,$folder_id,$user_id);
    }

    public function show(Folder $folder)
    {
        $subfiles=$this->getSubfiles($folder);
        $folders=$subfiles['folders'];
        $files=$subfiles['files'];
        return view('folders.show-folder',compact('folders','files','folder'));
    }

    public function edit(Folder $folder)
    {
        return view('folders.edit',[
            'folder'=>$folder
        ]);
    }

    public function update(UpdateFolderRequest $request, Folder $folder)
    {
        if(!auth()->user()->doesFolderExists($request->folder_name,$folder->folder_id)){
            $folder->update([
                'users_name'=>$request->folder_name,
            ]);
            return redirect()->route('files.index');
        }
    }

    public function destroy(Folder $folder)
    {
        while($folder->folders()->count()>0){
            foreach($folder->folders as $subfolder){
                $this->destroy($subfolder);
            }
        }
        $files=$folder->files;
        $folders=$folder->folders;
        foreach ($files as $f1){
            $extension=auth()->user()->extensions()->where('name',$f1->extension)->first();
            $extension->update(['number_of_files'=>$extension->number_of_files-1]);
            $f1->delete();
            Storage::delete($f1->system_path);
        }
        foreach ($folders as $f2){
            $f2->delete();
            Storage::deleteDirectory($f2->system_name);
        }
        $folder->delete();
        Storage::deleteDirectory($folder->system_name);
        return redirect()->route('files.index');
    }

    public function getSubfiles(Folder $folder){
        $files=$folder->subfiles();
        $folders=$folder->subfolders();
        foreach ($folders as $folder){
            $folder['size']=$folder->size_of_folder_in_kb;
        }
        return ['folders'=>$folders,'files'=>$files];
    }

    public function setShareFolder(Folder $folder){
        $usersToChoose=$folder->getUsersToShare();
        return view('folders.share-folder',compact('usersToChoose','folder'));
    }

    public function shareFolder(Request $request,Folder $folder){
        $users=$request->users;
        foreach($users as $user){
            $a=Share::query()->firstOrCreate([
                'user_id_1'=>auth()->id(),
                'user_id_2'=>$user,
                'folder_id'=>$folder->id
            ],[
                'user_id_1'=>auth()->id(),
                'user_id_2'=>$user,
                'folder_id'=>$folder->id
            ]);
            $userToMail=User::query()->where('id',$user)->first();
            $userToMail->notify(new SharedFolderNotification($userToMail,auth()->user(),$folder));
        }
        return redirect()->route('files.index');
    }

    public function getUsersSharedWith(Folder $folder){
        return $folder->getUsersSharedWith();
    }

    public function removeSharedFolder(Folder $folder,User $user){
        $folder->shares()->where('user_id_2',$user->id)->delete();
        return redirect()->route('files.index');
    }
}

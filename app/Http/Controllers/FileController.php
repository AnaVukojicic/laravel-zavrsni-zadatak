<?php

namespace App\Http\Controllers;

use App\Mail\SharedFileMail;
use App\Models\File;
use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\UpdateFileRequest;
use App\Models\Folder;
use App\Models\Share;
use App\Models\SharedFile;
use App\Models\User;
use App\Notifications\SharedFileNotification;
use Faker\Extension\Extension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function index()
    {
        $rootFiles=auth()->user()->getRootFiles();
        $rootFolders=auth()->user()->getRootFolders();
        return view('files-folders.index',compact('rootFiles','rootFolders'));
    }

    public function create()
    {
        //
    }

    public function storeHelper($request,$folder_id,$user_id,$available_storage){
        $file=$request->file;
        if(!Storage::exists('files')){
            Storage::makeDirectory('files');
        }

        if(!auth()->user()->doesFileExists($request->file_name,$folder_id,$file->getClientOriginalExtension())
            && $file->getSize()<=$available_storage) {
            File::query()->create([
                'user_id'=>$user_id,
                'users_name' => $request->get('file_name'),
                'system_path' => Storage::put('files', $file),
                'folder_id' => $folder_id,
                'size' => $file->getSize(),
                'extension' => $file->getClientOriginalExtension()
            ]);
            if(!auth()->user()->doesUserHasExtension($file->getClientOriginalExtension())){
                auth()->user()->extensions()->create(['name'=>$file->getClientOriginalExtension(),'number_of_files'=>1]);
            }else{
                $extension=auth()->user()->extensions()->where('name',$file->getClientOriginalExtension())->first();
                $oldNumberOfFiles=$extension->number_of_files;
                $extension->update(['number_of_files'=>$oldNumberOfFiles+1]);
            }
            return redirect()->route('files.index');
        }else{
            return redirect()->route('add-new');
        }
    }

    public function store(StoreFileRequest $request)
    {
        $user_id=auth()->id();
        $folder_id=null;
        $available_storage=auth()->user()->availableStorage();
        return $this->storeHelper($request,$folder_id,$user_id,$available_storage);
    }

    public function storeToExisting(StoreFileRequest $request,Folder $folder){
        $user_id=auth()->id();
        $folder_id=$folder->id;
        $available_storage=auth()->user()->availableStorage();
        return $this->storeHelper($request,$folder_id,$user_id,$available_storage);
    }

    public function show(File $file)
    {
        //
    }

    public function edit(File $file)
    {
        return view('files.edit',[
            'file'=>$file
        ]);
    }

    public function update(UpdateFileRequest $request, File $file)
    {
        if(!auth()->user()->doesFileExists($request->file_name,$file->folder_id,$file->extension)){
            $file->update([
                'users_name'=>$request->file_name,
            ]);
            return redirect()->route('files.index');
        }
    }

    public function destroy(File $file)
    {
        $extension=auth()->user()->extensions()->where('name',$file->extension)->first();
        $extension->update(['number_of_files'=>$extension->number_of_files-1]);
        Storage::delete($file->system_path);
        $file->delete();
        return redirect()->route('files.index');
    }

    public function getShared(){
        $sharedFiles=auth()->user()->getAllFilesSharedWithUser();
        return view('files-folders.shared',compact('sharedFiles'));
    }

    public function setShareFile(File $file){
        $usersToChoose=$file->getUsersToShare();
        return view('files.share-file',compact('usersToChoose','file'));
    }

    public static function shareFile(Request $request,File $file){
        $users=$request->users;
        foreach($users as $user){
            $a=Share::query()->firstOrCreate([
                'user_id_1'=>auth()->id(),
                'user_id_2'=>$user,
                'file_id'=>$file->id
            ],[
                'user_id_1'=>auth()->id(),
                'user_id_2'=>$user,
                'file_id'=>$file->id
            ]);
            $userToMail=User::query()->where('id',$user)->first();
            $userToMail->notify(new SharedFileNotification($userToMail,auth()->user(),$file));
        }
        return redirect()->route('files.index');
    }

    public function getUsersSharedWith(File $file){
        return $file->getUsersSharedWith();
    }

    public static function removeSharedFile(File $file,User $user){
        $file->shares()->where('user_id_2',$user->id)->delete();
        return redirect()->route('files.index');
    }

    public function downloadFile(File $file){
        return Storage::download($file->system_path,$file->users_name.'.'.$file->extension);
    }

}

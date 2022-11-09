<?php

namespace App\Http\Controllers;

use App\Models\Extension;
use App\Models\File;
use App\Models\Folder;
use App\Models\Share;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usedStorage=auth()->user()->used_storage_in_mb;
        $extensions=auth()->user()->extensions()->orderBy('id')->get();
        $sharedFiles=auth()->user()->get3FilesSharedWithUser();
        $totalFiles=File::query()->where('user_id',auth()->id())->count();
        return view('home',compact('usedStorage','extensions','sharedFiles','totalFiles'));
    }

    public function openShared(Folder $folder){
        $subfolders=$folder->getSharedSubfiles()['folders'];
        $subfiles=$folder->getSharedSubfiles()['files'];
        return view('folders.open-shared-folder',compact('folder','subfiles','subfolders'));
    }

    public function getSubfiles(Folder $folder){
        return $folder->getSharedSubfiles();
    }

    public function showFolder(Folder $folder){
        $subfolders=$folder->getSharedSubfiles()['folders'];
        $subfiles=$folder->getSharedSubfiles()['files'];
        return view('folders.show-shared',compact('folder','subfiles','subfolders'));
    }

    public function showUsers(){
        abort_if(!auth()->user()->is_superadmin,403,'You are not authorized!');
        $users=User::query()->orderBy('email')->get();
        return view('admin.users',compact('users'));
    }

    public function updateStorage(Request $request,User $user){
        $user=User::query()->where('id',$user->id);
        $user->update(['max_storage_gb'=>$request->max_storage]);
        return redirect()->route('admin.users');
    }
}

<?php

use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\HomeController;
use App\Models\Folder;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/auth/google/redirect', [SocialiteController::class,'googleRedirect'])->name('login.google.redirect');
Route::get('/auth/google/callback', [SocialiteController::class,'googleCallback'])->name('login.google.callback');

Route::get('/auth/twitter/redirect', [SocialiteController::class,'twitterRedirect'])->name('login.twitter.redirect');
Route::get('/auth/twitter/callback', [SocialiteController::class,'twitterCallback'])->name('login.twitter.callback');


Route::get('/auth/facebook/redirect', [SocialiteController::class,'facebookRedirect'])->name('login.facebook.redirect');
Route::get('/auth/facebook/callback', [SocialiteController::class,'facebookCallback'])->name('login.facebook.callback');

Route::group(['middleware'=>'auth'],function(){
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/files/shared',[FileController::class,'getShared'])->name('shared-files');
    Route::resource('/files', FileController::class);
    Route::resource('/folders', FolderController::class);
    Route::view('/create-new/','files-folders.create')->name('add-new');
    Route::get('/folders/{folder}/add-to-existing',[FolderController::class,'createInExisting'])->name('add-new-to-existing');
    Route::get('/get-subfiles/{folder}',[FolderController::class,'getSubfiles'])->name('get-subfiles');
    Route::post('/folders/store-to-existing/{folder}',[FolderController::class,'storeToExisting'])->name('folders.store-to-existing');
    Route::post('/files/store-to-existing/{folder}',[FileController::class,'storeToExisting'])->name('files.store-to-existing');

    Route::get('/files/share-file/{file}',[FileController::class,'setShareFile'])->name('files.set-share-file');
    Route::get('/files/get-share-users/{file}',[FileController::class,'getUsersSharedWith'])->name('files.get-share-users');
    Route::post('/files/share-file/{file}',[FileController::class,'shareFile'])->name('files.share-file');
    Route::delete('/files/{file}/remove-share-file/{user}',[FileController::class,'removeSharedFile'])->name('files.remove-share-file');

    Route::get('/folders/share-folder/{folder}',[FolderController::class,'setShareFolder'])->name('folders.set-share-folder');
    Route::get('/folders/get-share-users/{folder}',[FolderController::class,'getUsersSharedWith'])->name('folders.get-share-users');
    Route::post('/folders/share-folder/{folder}',[FolderController::class,'shareFolder'])->name('folders.share-folder');
    Route::delete('/folders/{folder}/remove-share-folder/{user}',[FolderController::class,'removeSharedFolder'])->name('folders.remove-share-folder');

    Route::get('/folders/open-shared/{folder}',[HomeController::class,'openShared'])->name('folders.open-shared');
    Route::get('/get-shared-subfiles/{folder}',[HomeController::class,'getSubfiles'])->name('get-shared-subfiles');
    Route::get('/shared/show/{folder}',[HomeController::class,'showFolder'])->name('show-shared');

    Route::get('/download-file/{file}',[FileController::class,'downloadFile'])->name('download-file');

    Route::get('/admin/users',[HomeController::class,'showUsers'])->name('admin.users');
    Route::put('/admin/{user}/update',[HomeController::class,'updateStorage'])->name('admin.update-user');
});

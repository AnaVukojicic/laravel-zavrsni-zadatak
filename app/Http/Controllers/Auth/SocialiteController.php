<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function googleRedirect() {
        return Socialite::driver('google')->redirect();
    }

    public function googleCallback(){
        $googleUser = Socialite::driver('google')->stateless()->user();
        return $this->helperLogin($googleUser);
    }

    public function twitterRedirect() {
        return Socialite::driver('twitter-oauth-2')->redirect();
    }

    public function twitterCallback(){
        $twitterUser=Socialite::driver('twitter-oauth-2')->stateless()->user();
        return $this->helperLogin($twitterUser);
    }

    public function facebookRedirect() {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(){
        $facebookUser=Socialite::driver('twitter-oauth-2')->stateless()->user();
        return $this->helperLogin($facebookUser);
    }

    public function helperLogin($user){
        $existingUser=User::query()->updateOrCreate([
            'email' => $user->getEmail()
        ], [
            'name' =>$user->getName(),
            'password'=>Hash::make(Str::random(10))
        ]);
        Auth::login($existingUser);
        return redirect()->route('home');
    }
}

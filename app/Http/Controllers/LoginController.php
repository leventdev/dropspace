<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    //
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    public function goToUpload(Request $request){
        //Check if ds_security_enabled is true, if it is, check if the user is authenticated.
        if(config('dropspace.ds_security_enabled')== true){
            if(Auth::check()){
                return view('upload');
            }
            return view('sign-in');
        }else{
            return view('upload');
        }
    }

    public function authenticate(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember-me');
        Log::info('Attempting to authenticate user: ' . $email);
        if(Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            // Authentication passed...
            Log::info('Authentication successful');
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        Log::info('Authentication failed at' . date('Y-m-d H:i:s'));
        //Pass the error message to the view

        return back()->withErrors(['email' => 'These credentials do not match our records.',]);
    }
}

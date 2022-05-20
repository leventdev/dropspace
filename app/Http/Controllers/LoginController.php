<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function settings(Request $request){
        //Get user's details
        $user = Auth::user();
        if(Auth::check()){
            $name = $user->ename;
            $company = $user->ecompany;
            return view('settings', ['name' => $name, 'company' => $company]);
        }else{
            return redirect('/');
        }
    }

    public function updateSettings(Request $request){
        //Type: post call
        //Check authentication, if password is not empty, update the user's password.
        //Check if the name changed, if it did, update the user's name.
        //Check if the company changed, if it did, update the user's company.
        //Return to the settings page.
        $authuser = Auth::user();
        if(Auth::check()){
            //Get user's details
            $user = User::find($authuser->id);
            if($request->input('password') != ''){
                $user->password = bcrypt($request->input('password'));
            }
            if($request->input('name') != $user->ename){
                $user->ename = $request->input('name');
                if($request->input('name') == ''){
                    $user->ename = null;
                }
            }
            if($request->input('company') != $user->ecompany){
                $user->ecompany = $request->input('company');
                if($request->input('company') == ''){
                    $user->ecompany = null;
                }
            }
            $user->save();
            return redirect('/settings');
        }else{
            return redirect('/');
        }
    }
}

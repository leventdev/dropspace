<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Invite;
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

    public function authenticate_cli(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        Log::info("[CLI] Attempting to authenticate user with email: ".$email);
        if(Auth::attempt(['email' => $email, 'password' => $password])){
            Log::info("[CLI] User authenticated successfully");
            //Generate new CLI key for user
            $user = Auth::user();
            $user = User::find($user->id);
            $user->cli_key = bin2hex(random_bytes(16));
            $user->save();
            return response()->json(['success'=>true, 'cli_key'=>$user->cli_key]);
        }
        Log::info("[CLI] User authentication failed at" . date('Y-m-d H:i:s'));
        return 'Authentication failed';
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

    public function invite($id, Request $request){
        //Check if invite exists
        if(Invite::where('code', $id)->exists()){
            //Chekc if invite is used
            $invite = Invite::where('code', $id)->first();
            if($invite->used == true){
                //Invite is used
                return view('download-error', ['error' => 'This invite has already been used.']);
            }
            //Invite is not used
            //Display the register page
            return view('invite', ['invite_code' => $id]);
        }else{
            return view('download-error', ['error' => 'Invite does not exist.']);
        }
    }

    public function useInvite(Request $request){
        if(Invite::where('code', $request->input('invite_code'))->exists()){
            $invite = Invite::where('code', $request->input('invite_code'))->first();
            if($invite->used == true){
                //Invite is used
                return view('download-error', ['error' => 'This invite has already been used.']);
            }
            //Invite is not used
            /*$name = $this->ask('What should the username be?');
            $email = $this->ask('What should the email be?');
            $password = $this->secret('What should the password be? (The input is hidden)');
            $ecompany = $this->ask('Where does this user work? (Leave blank if you don\'t want to enter a company)');
            $ename = $this->ask('How is this user called? (Leave blank if you don\'t want to enter a name)');*/
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                return back()->withErrors(['email' => 'This email is already registered.',]);
            }
            $user = User::where('name', $request->input('name'))->first();
            if ($user) {
                return back()->withErrors(['email' => 'This username is already registered.',]);
            }
            if($request->input('company') == ''){
                $ecompany = null;
            }
            if($request->input('ename') == ''){
                $ename = null;
            }
            User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'ecompany' => $request->input('company'),
                'ename' => $request->input('ename'),
            ]);
            $invite->used = true;
            $invite->save();
            return redirect('/');
        }else{
            return view('download-error', ['error' => 'Invite does not exist.']);
        }
    }
}

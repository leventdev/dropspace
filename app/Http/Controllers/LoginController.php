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
    /**
     * It logs out the user and redirects them to the homepage
     * 
     * @param Request request The request object represents the HTTP request and has properties for the
     * request query string, parameters, body, HTTP method, and so on.
     * 
     * @return A redirect to the root route.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * If the security is enabled, check if the user is logged in, if they are, show them the upload
     * page, if they aren't, show them the sign in page. If the security is disabled, show them the
     * upload page
     * 
     * @param Request request The request object.
     * 
     * @return A view.
     */
    public function goToUpload(Request $request)
    {
        if (config('dropspace.ds_security_enabled') == true) {
            if (Auth::check()) {
                return view('upload');
            }
            return view('sign-in');
        } else {
            return view('upload');
        }
    }

    /**
     * Authenticates the user and redirects them to the upload page if they are authenticated, or to the
     * sign in page if the credentials are incorrect.
     * 
     * @param Request request The request object.
     * 
     * @return The user is being returned.
     */
    public function authenticate(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        $remember = $request->input('remember-me');
        Log::info('Attempting to authenticate user: ' . $email);
        if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
            // Authentication passed...
            Log::info('Authentication successful');
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
        Log::info('Authentication failed at' . date('Y-m-d H:i:s'));
        //Pass the error message to the view

        return back()->withErrors(['email' => 'These credentials do not match our records.',]);
    }

    /**
     * The function checks if the user is logged in, if so, it gets the user's details and passes them
     * to the settings view
     * 
     * @param Request request This is the request object that contains all the information about the
     * request.
     * 
     * @return The user's details are being returned.
     */
    public function settings(Request $request)
    {
        //Get user's details
        $user = Auth::user();
        if (Auth::check()) {
            $name = $user->ename;
            $company = $user->ecompany;
            return view('settings', ['name' => $name, 'company' => $company]);
        } else {
            return redirect('/');
        }
    }

    /**
     * This function updates the user's settings.
     * 
     * In detail:
     * 1. Authenticate the user
     * 2. Get the user's details
     * 3. If password is not empty, update the user's password
     * 4. If name is not empty, update the user's name
     * 5. If company is not empty, update the user's company
     * 6. Save the user's updated details
     * 7. Redirect the user to the settings page
     * 
     * @param Request request The request object.
     * 
     * @return The user's details are being returned.
     */
    public function updateSettings(Request $request)
    {
        $authuser = Auth::user();
        if (Auth::check()) {
            //Get user's details
            $user = User::find($authuser->id);
            if ($request->input('password') != '') {
                $user->password = bcrypt($request->input('password'));
            }
            if ($request->input('name') != $user->ename) {
                $user->ename = $request->input('name');
                if ($request->input('name') == '') {
                    $user->ename = null;
                }
            }
            if ($request->input('company') != $user->ecompany) {
                $user->ecompany = $request->input('company');
                if ($request->input('company') == '') {
                    $user->ecompany = null;
                }
            }
            $user->save();
            return redirect('/settings');
        } else {
            return redirect('/');
        }
    }

    /**
     * It checks if the invite exists, if it does, it checks if it's used, if it's not, it displays the
     * register page
     * 
     * @param id The invite code
     * @param Request request The request object
     * 
     * @return A view
     */
    public function invite($id, Request $request)
    {
        //Check if invite exists
        if (Invite::where('code', $id)->exists()) {
            //Chekc if invite is used
            $invite = Invite::where('code', $id)->first();
            if ($invite->used == true) {
                //Invite is used
                return view('download-error', ['error' => 'This invite has already been used.']);
            }
            //Invite is not used
            //Display the register page
            return view('invite', ['invite_code' => $id]);
        } else {
            return view('download-error', ['error' => 'Invite does not exist.']);
        }
    }

    /**
     * This function registers a new user from an invite.
     * 
     * In detail:
     * 1. Check if the invite exists
     * 2. Check if the invite is used
     * 3. Check if the email is already in use
     * 4. Check if the username is already in use
     * 5. Create a new user
     * 6. Set the invite to used
     * 7. Redirect the user to the login page
     * 
     * @param Request request The request object.
     * 
     * @return The user is being returned.
     */
    public function useInvite(Request $request)
    {
        if (Invite::where('code', $request->input('invite_code'))->exists()) {
            $invite = Invite::where('code', $request->input('invite_code'))->first();
            if ($invite->used == true) {
                //Invite is used
                return view('download-error', ['error' => 'This invite has already been used.']);
            }
            $user = User::where('email', $request->input('email'))->first();
            if ($user) {
                return back()->withErrors(['email' => 'This email is already registered.',]);
            }
            $user = User::where('name', $request->input('name'))->first();
            if ($user) {
                return back()->withErrors(['email' => 'This username is already registered.',]);
            }
            if ($request->input('company') == '') {
                $ecompany = null;
            }
            if ($request->input('ename') == '') {
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
        } else {
            return view('download-error', ['error' => 'Invite does not exist.']);
        }
    }
}

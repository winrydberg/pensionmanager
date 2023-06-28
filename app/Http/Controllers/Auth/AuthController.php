<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\master\LoginRequestData;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    /**
     * RENDER LOGIN PAGE
     */
    public function loginPage(){
        return view('master.login');
    }

    /**
     * AUTHENTICATE AND LOGIN USER
     */
    public function authenticateUser(LoginRequestData $request){
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if($user && ($user->is_active ==false || $user->is_active ==null)){
            return redirect()->back()->with('error', 'User account has been deactivated. Contact administrators for help');
        }
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->to('/dashboard');
        } else{
            return redirect()->back()->with('error', 'Invalid Login Credentials');
        }
    }

    /**
     * FORGOT PASSWORD
     */
    public function forgotPasswordPage(){
        return view('master.forgotpassword');
    }


    /**
     * LOGOUT USER
     */
    public function logoutUser(){
        Auth::logout();
        return redirect()->to('/login');
    }
}
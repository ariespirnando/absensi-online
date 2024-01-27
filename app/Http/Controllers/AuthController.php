<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function index(){
        $user = Auth::user();
        if($user){
            if($user->group_level =='ADMIN'){
                return redirect()->intended('admin');
            }
            else if($user->group_level =='SISWA'){
                return redirect()->intended('user');
            }
        }
        return view('auth.sign-in');
    }

    public function forgot_password(){
        return view('auth.forgot-password');
    }

    public function proses_login(Request $request){
        $request->validate([
            'username'=>'required',
            'password'=>'required'
        ]);
        $credential = $request->only('username','password');
        if(Auth::attempt($credential)){
            $user =  Auth::user();
            if($user->group_level =='ADMIN'){
                return redirect()->intended('admin');
            }else if($user->group_level =='SISWA'){
                return redirect()->intended('user');
            }
            return redirect()->intended('/');
        }
        return redirect('/')
            ->withInput()
            ->withErrors(['login_gagal'=>'These credentials does not match our records']);
    }

    public function proses_forgot_password(Request $request){
        $request->validate([
            'email'=>'required'
        ]);
        $credential = $request->only('username','password');
        if(Auth::attempt($credential)){
            $user =  Auth::user();
            if($user->group_level =='ADMIN'){
                return redirect()->intended('admin');
            }else if($user->group_level =='SISWA'){
                return redirect()->intended('user');
            }
            return redirect()->intended('/');
        }
        return redirect('/')
            ->withInput()
            ->withErrors(['login_gagal'=>'These credentials does not match our records']);
    }

    public function logout(Request $request){
        $request->session()->flush();
        Auth::logout();
        return Redirect('/');
    }
}

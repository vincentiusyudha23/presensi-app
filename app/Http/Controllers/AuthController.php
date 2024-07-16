<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('dashboard');
        } else {
            return view('login');
        }
    }
    public function actionLogin(Request $request)
    {
        $data = [
            'username' => $request->username,
            'password' => $request->password
        ];
        if (Auth::Attempt($data)) {
            return redirect('dashboard');
        } else {
            Session::flash('error', 'Email atau Password Salah');
            return redirect('/');
        }
    }
    public function actionlogout()
    {
        Auth::logout();
        return redirect('/');
    }
}

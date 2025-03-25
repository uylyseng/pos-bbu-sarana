<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Hash;



class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/home');
        }
        return view('auth.login');
    }

    public function auth_login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            if (Auth::user()->status === 'active') {
                $request->session()->regenerate();
                if (Auth::user()->email === 'admin@yandex.com') {
                    return redirect('/dashboard/sale')->with('success', 'You are logged in successfully.');
                } elseif (Auth::user()->email === 'seller@gmail.com') {
                    return redirect('/shifts/create')->with('success', 'You are logged in successfully.');
                } else {
                    return redirect('/home')->with('success', 'You are logged in successfully.');
                }
            } else {
                Auth::logout();
                return redirect()->back()->with('error', 'Your account is not active.');
            }
        }

        return redirect()->back()->with('error', 'Your email or password is incorrect.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url('/'));
    }
}

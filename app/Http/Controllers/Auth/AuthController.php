<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (auth()->user()->is_admin) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('home'));
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $v = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:30',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $v['name'],
            'email'    => $v['email'],
            'phone'    => $v['phone'] ?? null,
            'password' => $v['password'], // hashed cast handles this automatically
            'is_admin' => false,
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to DriveFlow!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/login')->with('success', 'Registration successful. Please login.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    
    if ($user->session_id) {
        $activeSession = DB::table('sessions')
            ->where('id', $user->session_id)
            ->first();

        if ($activeSession) {
            return back()->withErrors([
                'email' => 'This account is already logged in on another browser or system.',
            ])->onlyInput('email');
        }
    }

    Auth::login($user);

    $request->session()->regenerate();

    $user->session_id = $request->session()->getId();
    $user->save();

    return redirect('/dashboard');
}

    public function dashboard()
    {
        return view('dashboard');
    }

   public function logout(Request $request)
{
    $user = Auth::user();

    if ($user) {
        $user->session_id = null;
        $user->save();
    }

    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}
}
<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login', ['social' => ['google' => SocialAuthController::enabled('google')]]); }
    public function showRegister() { return view('auth.register'); }

    public function login(Request $r)
    {
        $cred = $r->validate(['email' => 'required|email', 'password' => 'required']);
        if (Auth::attempt($cred, $r->boolean('remember'))) {
            $r->session()->regenerate();
            return redirect()->intended(Auth::user()->isAdmin() ? '/admin' : '/dashboard');
        }
        return back()->withErrors(['email' => 'Email atau password salah.'])->onlyInput('email');
    }

    public function register(Request $r)
    {
        $d = $r->validate(['name' => 'required|max:100', 'email' => 'required|email|unique:users', 'password' => 'required|min:8|confirmed']);
        Auth::login(User::create($d));
        $r->session()->regenerate();
        return redirect('/dashboard')->with('ok', 'Selamat datang di Oddesey! 🚀');
    }

    public function logout(Request $r)
    {
        Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken();
        return redirect('/');
    }
}

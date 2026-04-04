<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request, LogService $logService)
    {
        $request->validate([
            'firstName' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->firstName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => 2,
        ]);

        $logService->log('register', 'New user registered');

        Auth::attempt($request->only('email', 'password'));

        $logService->log('login', 'User logged in');

        return redirect('/dashboard')
            ->with('success', 'Account created successfully.');
    }

    public function login(Request $request, LogService $logService)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->is_blocked) {
                Auth::logout();

                $logService->log('login_block', "Blocked user {$user->email} login attempt");

                return redirect()->route('login')
                    ->withErrors(['email' => 'Your account is currently blocked. Please contact us for further details.']);
            }

            $request->session()->regenerate();

            $logService->log('login', 'User logged in');

            if(auth()->user()->isAdmin()) {
                return redirect('/admin/dashboard');
            }
            else {
                return redirect('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Incorrect email or password.',
        ]);
    }

    public function logout(Request $request, LogService $logService)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $logService->log('logout', 'User logged out');

        return redirect('/login');
    }
}

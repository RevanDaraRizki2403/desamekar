<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        // Menunjuk ke resources/views/pages/auth/login.blade.php
        return view('pages.auth.login');
    }

    public function authenticate(Request $request)
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 1. Cek apakah status akun sudah approved
            if (isset($user->status) && $user->status !== 'approved') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Akun Anda belum disetujui atau sedang tidak aktif.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // 2. Redirect ke dashboard sesuai role_id
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /**
     * Helper function untuk mengarahkan user berdasarkan role_id
     */
    private function redirectBasedOnRole($user)
    {
        switch ((int) $user->role_id) {
            case 1:
                return redirect()->route('admin.dashboard');   // Admin Farming
            case 2:
                return redirect()->route('karyawan.dashboard'); // Karyawan Farm
            case 3:
                return redirect()->route('manager.dashboard');  // Manager
            default:
                return redirect()->route('dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function get_login_page()
    {
        return view('welcome');
    }

    public function get_registrasi_page()
    {
        return view('auth.registrasi');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => 'required|max:30|unique:users,nip',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => ucwords(strtolower($validated['nama'])),
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']), 
            'role' => 'guru',
        ]);

        Auth::login($user);

        return redirect(url('/'))->with([
            'success' => [
                "title" => "Berhasil mendaftarkan akun.",
            ],
        ]);
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user dengan email tersebut ada
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->getOriginal('password'))) {
            return redirect(url('/'))->with([
                'error' => [
                    "title" => "Email atau password salah.",
                ],
            ])->withInput();
        }        

        // Login user
        Auth::login($user);

        // Redirect berdasarkan role
        return match ($user->role) {
            'guru' => redirect(url('/dashboard-guru'))->with([
                'success' => [
                    "title" => "Berhasil masuk sebagai guru.",
                ],
            ]),
            // 'admin' => redirect()->route('dashboard.admin'),
            // 'kepsek' => redirect()->route('dashboard.kepsek'),
            default => redirect('/'),
        };
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url('/'))->with([
            'success' => [
                "title" => "Berhasil keluar",
            ]
        ]);
    }
}

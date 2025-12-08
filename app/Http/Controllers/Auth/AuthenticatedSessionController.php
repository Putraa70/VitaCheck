<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show login page.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Validasi + autentikasi default Breeze
        $request->authenticate();

        // Regenerate session agar aman
        $request->session()->regenerate();

        // ============================
        // 🎯 REDIRECT BERDASARKAN PERAN
        // ============================
        $user = $request->user();

        if ($request->user()->peran === 'admin') {
            return redirect()->route('admin.dasbor_admin'); // <- sesuai route:list kamu
        }
        return redirect()->route('dasbor'); // user

    }

    /**
     * Logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // SELALU ke login
        return redirect()->route('login')->with('sukses', 'Anda telah keluar.');
    }
}

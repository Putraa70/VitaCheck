<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    /**
     * Redirect ke Google.
     */
    public function redirect(): RedirectResponse
    {
        // Cek dulu apakah config google sudah kebaca
        if (! config('services.google.client_id')) {
            // biar kelihatan di layar
            abort(500, 'Google Client ID belum dikonfigurasi di config/services.php atau .env');
        }

        return Socialite::driver('google')->redirect();
    }

    /**
     * Callback dari Google.
     */
    public function callback(): RedirectResponse
    {
        try {
            // ambil data user dari Google
            $googleUser = Socialite::driver('google')->user();
        } catch (Throwable $e) {
            // log error dan kirim pesan ke halaman login
            report($e);

            return redirect()
                ->route('login')
                ->with('error_google', 'Gagal login dengan Google: ' . $e->getMessage());
        }

        // Cari user berdasarkan email
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            // Kalau belum ada, buat user baru
            $user = User::create([
                'name'              => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Pengguna Google',
                'email'             => $googleUser->getEmail(),
                'email_verified_at' => now(),
                'google_id'         => $googleUser->getId(),
                'password'          => bcrypt(Str::random(32)), // random karena login pakai Google
                'peran'             => 'mahasiswa', // sesuaikan default peran di sistemmu
            ]);
        } else {
            // Update google_id kalau belum ada
            if (empty($user->google_id)) {
                $user->google_id = $googleUser->getId();
                $user->save();
            }
        }

        // Login-kan user
        Auth::login($user, true);

        // redirect sesuai peran (sama seperti di AuthenticatedSessionController)
        if ($user->peran === 'admin') {
            return redirect()->route('admin.dasbor_admin');
        }

        return redirect()->route('dasbor');
    }
}

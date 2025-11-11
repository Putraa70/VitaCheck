<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Pemesanan;
use App\Policies\PemesananPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pemesanan::class => PemesananPolicy::class,
    ];

    public function boot(): void
    {
        Gate::define('admin', fn($user) => $user->peran === 'admin');
    }
}

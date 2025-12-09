<?php

// app/Providers/AuthServiceProvider.php

namespace App\Providers;

use App\Models\Pemesanan;
use App\Models\User;
use App\Policies\PemesananPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Pemesanan::class => PemesananPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function (User $user) {
            return $user->peran === 'admin'; // atau $user->isAdmin();
        });
    }
}

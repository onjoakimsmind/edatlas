<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(GateContract $gate): void
    {
        $this->registerPolicies($gate);

        $gate->define('admin', function($user) {
            return $user->role === 'admin';
        });

        $gate->define('moderator', function($user) {
            return $user->role === 'moderator';
        });

        $gate->define('user', function($user) {
            return $user->role === 'user';
        });
    }
}

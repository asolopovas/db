<?php

namespace App\Providers;

use App\Policies\UserPolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param GateContract $gate
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('is_admin', [UserPolicy::class, 'is_admin']);
    }
}

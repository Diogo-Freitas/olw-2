<?php

namespace App\Providers;

use App\Models\User;
use App\Enums\RoleEnum;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // $proxy_url = config('route.proxy_url');
        // $proxy_scheme = config('route.proxy_scheme');

        // if (!empty($proxy_url)) {
        //     URL::forceRootUrl($proxy_url);
        // }

        // if (!empty($proxy_scheme)) {
        //     URL::forceScheme($proxy_scheme);
        // }

        Gate::define('impersonate', function(User $user){
            return $user->role_id === RoleEnum::ADMIN;
        });

        Gate::define('leavel-impersonate', function(User $user){
            return session()->has('impersonate') && User::find(session('impersonate'))->role_id === RoleEnum::ADMIN;
        });
    }
}

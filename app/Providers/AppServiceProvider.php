<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $user_roles = Auth::check() ? Auth::user()->roles->pluck('nombre')->toArray() : [];
            $view->with('user_roles', $user_roles);

            $tipo_usuario = Auth::check() ? Auth::user()->tipo : null;
            $view->with('tipo_usuario', $tipo_usuario);
            $estatus_usuario = Auth::check() ? Auth::user()->estatus : null;
            $view->with('estatus_usuario', $estatus_usuario);
        });
    }
}

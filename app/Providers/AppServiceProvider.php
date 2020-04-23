<?php
namespace App\Providers;

use Illuminate\Support\Facades\Blade;
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
        view()->composer('layouts.sidebar', function(\Illuminate\View\View $view) {
            $view->with('tagsCloud', \App\Tag::tagsCloud());
        });
        Blade::if('role', function ($role) {
            return \auth()->check() && \auth()->user()->hasRole($role);
        });
    }
}

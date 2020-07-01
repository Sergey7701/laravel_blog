<?php
namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\XHProfMiddleware;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(XHProfMiddleware::class);
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
        Blade::if('permission', function ($permissionSlug) {
            return \auth()->check() && \auth()->user()->hasPermission($permissionSlug);
        });
        Blade::if('authorized', function () {
            return \auth()->check();
        });
    }
}

<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Permission;
use Illuminate\Support\Facades\Gate;

class PermissionServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            Permission::all()->map(function ($permission) {
                Gate::define($permission->slug, function ($user) use ($permission) {
                    return $user->hasPermission($permission->slug);
                });
            });
        } catch (\Exception $e) {
            report($e);
            return false;
        }
    }
}

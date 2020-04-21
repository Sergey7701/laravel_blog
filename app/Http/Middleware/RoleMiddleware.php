<?php
namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;

class RoleMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role, $permission = null)
    {
        if (!Auth::check() || !auth()->user()->hasRole($role))  {
            //exit('Тут сработало!');
            abort(404);
        }
        if ($permission !== null && !auth()->user()->can($permission)) {
            abort(404);
        }
        return $next($request);
    }
}

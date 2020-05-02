<?php
namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class PermissionMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {
        if (!Auth::check() || !auth()->user()->role || !auth()->user()->can($permission)) {
            //exit('Тут сработало!');
            abort(404);
        }
//        if ($permission !== null && !auth()->user()->can($permission)) {
//            abort(404);
//        }
        return $next($request);
    }
}

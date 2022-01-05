<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class AutoCheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $routeName = $request->route()->getName(); //user.create
        $permission = Permission::whereRaw("FIND_IN_SET ('$routeName', route_name)")->first();
        if ($permission)
        {
            if (!$request->user()->can($permission->name))
            {
                abort(403);
            }
        }
//        else{
//            abort(403);
//        }
        return $next($request);
    }
}

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        if(!$request->user()->can($permission)){
            abort(403,'Unauthorized');
        }

        return $next($request);
    }
}

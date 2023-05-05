<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Util\AppConstant;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;

class HaveAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next):Response
    {
        if (auth()->check() && UserService::getRoles(auth()->id())=='ADMIN'){
            return $next($request);
        }
        return response()->json([
            // 'error' => UserService::getRoles(auth()->id()) for debug
            'error' => AppConstant::$PERMISSION_DENY
        ]);

        
    }
}

<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Проверка роли пользователя.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Проверяем, аутентифицирован ли пользователь
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if (!auth()->user()->hasRole($role)) {
            return response()->json(['message' => 'You do not have the required role to access this resource'], 403);
        }

        return $next($request);
    }
}


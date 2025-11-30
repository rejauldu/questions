<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleHierarchy
{
    /**
     * Role hierarchy (higher index = higher privileges)
     */
    protected $roles = [
        'guest' => 0,
        'user' => 1,
        'moderator' => 2,
        'admin' => 3,
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $minRole  Minimum role required to access
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $minRole)
    {
        $user = Auth::user();

        // If no user, treat as guest
        $userRole = $user?->role ?? 'guest';

        if (!isset($this->roles[$userRole])) {
            abort(403, 'Invalid role');
        }

        if (!isset($this->roles[$minRole])) {
            abort(500, 'Invalid middleware role configuration');
        }

        // Check hierarchy
        if ($this->roles[$userRole] < $this->roles[$minRole]) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
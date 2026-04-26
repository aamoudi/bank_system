<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SuperAdminPermissions
{
    public function handle(Request $request, Closure $next)
    {
        $admin = auth('admin')->user();

        if ($admin && $admin->id === 1) {
            Gate::before(function ($user, $ability) {
                if ($user instanceof \App\Models\Admin && $user->id === 1) {
                    return true;
                }
            });
        }

        return $next($request);
    }
}
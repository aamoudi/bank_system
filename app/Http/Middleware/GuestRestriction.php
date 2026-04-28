<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestRestriction
{
    // Block these URL patterns for guests completely (GET included)
    protected $blockedUrls = [
        'user-edit-profile',
        'user-update-profile',
        'user-edit-password',
        'user-update-password',
    ];

    public function handle(Request $request, Closure $next)
    {
        $user = auth('user')->user();

        if ($user && $user->email === 'guest@masareefi.com') {

            // Block profile/password pages entirely (even GET)
            foreach ($this->blockedUrls as $blockedUrl) {
                if ($request->is('cms/admin/' . $blockedUrl)) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message'  => '🔒 Guests cannot modify profile or password.',
                            'is_guest' => true
                        ], 403);
                    }
                    // Redirect to dashboard with a flash message
                    return redirect()->route('cms.dashboard')
                        ->with('guest_blocked', 'Guests cannot access profile settings.');
                }
            }

            // Block all write operations
            if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message'  => '🔒 You are logged in as a Guest. This action is not allowed.',
                        'is_guest' => true
                    ], 403);
                }
                return redirect()->back()->with('guest_error', 'You are a guest — view only access.');
            }
        }

        return $next($request);
    }
}
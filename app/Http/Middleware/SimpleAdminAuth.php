<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SimpleAdminAuth
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $adminUser = config('app.admin_username', env('ADMIN_USERNAME', 'admin'));
        $adminPass = config('app.admin_password', env('ADMIN_PASSWORD', 'password'));

        if ($request->getUser() !== $adminUser || $request->getPassword() !== $adminPass) {
            return response('Unauthorized.', 401, [
                'WWW-Authenticate' => 'Basic realm="NETSIGHT Admin Panel", charset="UTF-8"'
            ]);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        abort_unless($user->is_admin, 403, 'Unauthorized');

        return $next($request);
    }
}

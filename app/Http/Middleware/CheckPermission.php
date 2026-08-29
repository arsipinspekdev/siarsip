<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Check granular permission in database
        if (!$user->hasPermission($module, $action)) {
            abort(403, 'Anda tidak memiliki hak akses untuk melakukan tindakan ini (' . $module . ' - ' . $action . ').');
        }

        return $next($request);
    }
}

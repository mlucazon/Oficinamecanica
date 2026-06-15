<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearContaAccessWhenLeaving
{
    public function handle(Request $request, Closure $next): Response
    {
        $routeName = $request->route()?->getName();

        if (
            $request->hasSession()
            && $request->session()->has('conta_usuarios_acesso')
            && ! str_starts_with((string) $routeName, 'conta.usuarios')
        ) {
            $request->session()->forget('conta_usuarios_acesso');
        }

        return $next($request);
    }
}

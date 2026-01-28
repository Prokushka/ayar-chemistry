<?php

namespace App\Http\Middleware;

use Closure;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class BreadCrumbsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if (!$route) {
            return $next($request);
        }

        $name = $route->getName();
        $params = $route->parameters();

        if (Breadcrumbs::exists($name)) {
            $breadcrumbs = Breadcrumbs::generate($name, ...array_values($params));
        } else {
            $breadcrumbs = [];
        }

        Inertia::share('breadcrumbs', $breadcrumbs);

        return $next($request);
    }
}

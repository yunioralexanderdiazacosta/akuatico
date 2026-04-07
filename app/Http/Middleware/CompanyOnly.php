<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->isIndividual()) {
            return redirect()->route('user.listings')->with('error', __('Only companies can access this feature.'));
        }

        return $next($request);
    }
}

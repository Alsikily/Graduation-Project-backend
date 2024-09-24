<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class authAll {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {

        if (
            Auth::guard("studentApi") -> check() ||
            Auth::guard("teacherApi") -> check() ||
            Auth::guard("schoolApi") -> check() ||
            Auth::guard("parentApi") -> check()
        ) {

            return $next($request);
            
        } else {
            
            return redirect() -> route("login");

        }

    }
}

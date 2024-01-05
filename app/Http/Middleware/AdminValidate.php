<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(session('admin') != 'true'){
            if(session('staff') != 'true'){
                return redirect('/login');
            }
            else{
                return redirect('/admin_page');
            }
        }
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle( $request, Closure $next )
    {
        if( empty( $request->session()->get( 'us3r-id' ) ) ){

            return redirect( '/' );
        }

        return $next($request);
    }
}

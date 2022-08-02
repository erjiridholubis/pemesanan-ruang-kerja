<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use App\User;
use App\Level;

class UserLevel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $access = Level::where('id',Auth::id())->get()->first();
        if ($access->level !== 'admin') {
          return abort(403,'Anda tidak memiliki akses kehalaman ini');
        }

        return $next($request);
    }
}

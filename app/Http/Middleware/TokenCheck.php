<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\User;

class TokenCheck
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
    $access = User::where('api_token',$request->header('token'))->first();
    if ($access) {
      return $next($request);
    } else {
      return response()->json(["message"=>'Unauthorized'],401);
    }
  }
}

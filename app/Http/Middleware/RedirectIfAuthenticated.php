<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @param  string|null  $guard
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = null)
  {
    if (Auth::guard($guard)->check()) {
      return Auth::user()->shop;
      if (Auth::guard($guard)->user()->shop->verification_status) {
        return redirect('/home');
      } else {
        Auth::guard($guard)->logout();
        return back()->with('verification_fail', 'Your verification is under process, please wait.');
      }

    }

    return $next($request);
  }
}

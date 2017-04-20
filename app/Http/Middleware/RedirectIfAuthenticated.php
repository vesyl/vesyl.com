<?php

namespace FlashSale\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;

        $lang = \Session::get('user_locale');
//        echo'<pre>';print_r($lang);die("dxsvg");
        if ($lang != null) \App::setLocale($lang);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

//        die( \Session::get('user_locale'));
//        if ($this->auth->guest()) {
//            return redirect('/');
//        }

        return $next($request);
    }
}

<?php

namespace Edchant\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Authenticate
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
        if ($this->auth->guest()) { // guest
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                //return redirect()->guest('auth/login');
                //return redirect()->guest('auth.signin'); // changed for our needs
                return redirect()->route('auth.signin'); // changed again.. (ex unsigned-in user that try link '/user/someusername')               
            }
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\Response;
use App\Models\User;

class UserMiddleware
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
        $user = User::find($request->user_id);
        $response = new Response;
        if(!$user) return response()->json($response->get_response('01', null), 200);

        $request['user'] = $user;
        $request['response'] = $response;

        return $next($request);
    }
}

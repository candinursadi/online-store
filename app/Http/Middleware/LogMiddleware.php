<?php

namespace App\Http\Middleware;

use Closure;
use URL;
use App\Models\Log;

class LogMiddleware
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
        $response = $next($request);
        
        // LOG REQUEST RESPONSE
        $in = new Log;
        $in->url            = URL::current();
        $in->url_name       = $request->route()[1]['as'];
        $in->user_id        = $request->user_id;
        $in->request        = json_encode($request->all());
        $in->response       = $response->getContent();
        $in->error          = $response->exception;
        $in->ip             = $request->ip();
        $in->save();
        
        return $response;
    }
}

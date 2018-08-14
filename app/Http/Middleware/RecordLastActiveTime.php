<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class RecordLastActiveTime
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
        //如果用户登录的话
        if(Auth::check()){
            //记录用户最后一次访问的时间
            Auth::user()->recordLastActivedAt();
        }
        return $next($request);
    }
}

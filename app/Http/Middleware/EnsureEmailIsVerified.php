<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
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
        //判断是否通过了邮箱验证
        //三个判断条件
        //1.用户是否登陆了
        //2.是不是还未验证Email
        //3.并且访问的不是邮箱验证有关的网页
        if($request->user() && !$request->user()->hasVerifiedEmail() && !$request->is('email/*', 'logout')){

            //根据客户端返回对应的内容
            return $request->expectsJson()? abort(403, 'Your email address is not verified.') : redirect()->route('verification.notice');
        }
        return $next($request);
    }
}

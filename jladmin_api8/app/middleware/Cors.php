<?php

namespace app\middleware;

class Cors
{
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        // 设置跨域域名
        $origin = $request->header('origin');
        $response->header('Access-Control-Allow-Origin', '*');

        // 允许的头信息
        $response->header('Access-Control-Allow-Headers', '*');

        // 允许的请求方法
        $response->header('Access-Control-Allow-Methods', 'GET,POST,PATCH,PUT,DELETE,OPTIONS');

        // 允许Credentials
        $response->header('Access-Control-Allow-Credentials', 'true');

        // 缓存时间
        $response->header('Access-Control-Max-Age', '86400');

        return $response;
    }
}
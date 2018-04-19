<?php

namespace app\http\middleware;

class Theme
{
    public function handle($request, \Closure $next)
    {
        echo "theme middleware.";
        return $next($request);
    }
}

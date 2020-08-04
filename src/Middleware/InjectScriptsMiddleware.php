<?php

namespace BangNokia\ServeLiveReload\Middleware;

use BangNokia\ServeLiveReload\Injector;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InjectScriptsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            Cache::get('serve_websockets_running') === true
            && $request->getMethod() === Request::METHOD_GET
            && !$request->isXmlHttpRequest()
            && !$response instanceof JsonResponse
        ) {
            $response->setContent(
                (new Injector())->injectScripts($response->getContent())
            );
        }

        return $response;
    }
}

<?php

namespace BangNokia\ServeLiveReload;

use BangNokia\ServeLiveReload\Middleware\InjectScriptsMiddleware;
use Illuminate\Support\ServiceProvider;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views/', 'serve_livereload');

        $this->app['router']->pushMiddlewareToGroup('web', InjectScriptsMiddleware::class);
    }
}

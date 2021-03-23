<?php

namespace BangNokia\ServeLiveReload;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use BangNokia\ServeLiveReload\Middleware\InjectScriptsMiddleware;

class ResponseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../views/', 'serve_livereload');

        $this->app->make(Kernel::class)
            ->prependMiddlewareToGroup('web', InjectScriptsMiddleware::class);
    }
}

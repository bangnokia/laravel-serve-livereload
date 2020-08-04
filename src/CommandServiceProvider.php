<?php

namespace BangNokia\ServeLiveReload;

use BangNokia\ServeLiveReload\Commands\ServeCommand;
use BangNokia\ServeLiveReload\Commands\ServeHttpCommand;
use BangNokia\ServeLiveReload\Commands\ServeWebSocketsCommand;
use Carbon\Laravel\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class CommandServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        // override serve command
        $this->app->singleton('command.serve', function () {
            return new ServeCommand();
        });

        // register new commands
        $this->commands([
            ServeCommand::class,
            ServeHttpCommand::class,
            ServeWebSocketsCommand::class,
        ]);

        $this->mergeConfigFrom(__DIR__.'/../config/serve_livereload.php', 'serve_livereload');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/serve_livereload.php' => config_path('serve_livereload.php'),
        ]);
    }

    public function provides()
    {
        return ['command.serve'];
    }
}

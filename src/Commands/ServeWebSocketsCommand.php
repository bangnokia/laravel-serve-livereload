<?php

namespace BangNokia\ServeLiveReload\Commands;

use BangNokia\ServeLiveReload\Socket;
use BangNokia\ServeLiveReload\Watcher;
use Illuminate\Console\Command;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as LoopFactory;
use React\EventLoop\LoopInterface;
use React\Socket\Server;
use React\Socket\Server as Reactor;
use Symfony\Component\Finder\Finder;

class ServeWebSocketsCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve:websockets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Serve the application on the PHP development websockets server';

    /** @var bool */
    protected $hidden = true;

    /** @var LoopInterface */
    protected $loop;

    /** @var Server */
    protected $server;

    public static $port = 1322;

    public static $offset = 0;

    /**
     * Execute the console command.
     *
     * @throws \Exception
     *
     * @return int
     */
    public function handle()
    {
        $this->loop = LoopFactory::create();

        $this->loop->futureTick(function () {
            $this->line('<info>Websockets server started:</info> ws://127.0.0.1:'.self::port());
        });

        $this->startWatcher();

        $this->startServer();
    }

    protected function startWatcher()
    {
        $finder = (new Finder())->files()->in($this->dirs());

        (new Watcher($this->loop, $finder))->startWatching(function () {
            collect(Socket::$clients)->map(function (ConnectionInterface $client) {
                $client->send('reload');
            });
        });
    }

    protected function startServer()
    {
        try {
            $this->server = new IoServer(
                new HttpServer(new WsServer(new Socket())),
                new Reactor('127.0.0.1:'.self::port(), $this->loop),
                $this->loop
            );

            $this->server->run();
        } catch (\Exception $exception) {
            if (self::$offset < 10) {
                self::$offset++;
                $this->startServer();
            }
        }
    }

    protected function dirs()
    {
        return array_map(function ($dir) {
            return base_path('/'.$dir);
        }, config('serve_livereload.dirs'));
    }

    public static function port()
    {
        return self::$port + self::$offset;
    }
}

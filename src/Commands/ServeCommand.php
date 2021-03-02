<?php

namespace BangNokia\ServeLiveReload\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected $signature = 'serve
        {--host= : The host address to serve the application on}
        {--port= : The port to serve the application on}
        {--tries= : The max number of ports to attempt to serve from}
        {--no-reload : Do not reload the development server on .env file changes}';

    protected $description = 'Serve the application on the PHP development server';

    public function handle()
    {
        $phpBinaryPath = (new PhpExecutableFinder())->find(false);
        $artisanPath = base_path('artisan');

        $processes = [
            $httpProcess = new Process([$phpBinaryPath, $artisanPath, 'serve:http'] + $this->serveOptions()),
            $socketProcess = new Process([$phpBinaryPath, $artisanPath, 'serve:websockets']),
        ];

        while (count($processes)) {
            /* @var \Symfony\Component\Process\Process $process */
            foreach ($processes as $i => $process) {
                if (!$process->isStarted()) {
                    $process->setTimeout(null);
                    $process->start();
                    continue;
                }

                if ($info = trim($process->getIncrementalOutput())) {
                    $this->info($info);
                }

                if ($error = trim($process->getIncrementalErrorOutput())) {
                    $this->line($error);
                }

                if (!$process->isRunning()) {
                    unset($processes[$i]);
                }
            }
            sleep(1);
        }
    }

    public function serveOptions()
    {
        return collect($this->options())
            ->filter(function ($option) {
                return $option;
            })->map(function ($value, $key) {
                if (is_bool($value)) {
                    return "--{$key}";
                }

                return "--{$key}={$value}";
            })->values()->toArray();
    }
}

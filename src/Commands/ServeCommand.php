<?php

namespace BangNokia\ServeLiveReload\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ServeCommand extends Command
{
    protected $signature = 'serve';

    protected $description = 'Serve the application on the PHP development server';

    public function handle()
    {
        $phpBinaryPath = (new PhpExecutableFinder())->find(false);
        $artisanPath = base_path('artisan');

        $processes = [
            $httpProcess = new Process([$phpBinaryPath, $artisanPath, 'serve:http']),
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
}

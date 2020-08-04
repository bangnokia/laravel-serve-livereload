<?php

namespace BangNokia\ServeLiveReload\Commands;

use Illuminate\Foundation\Console\ServeCommand;

class ServeHttpCommand extends ServeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'serve:http';

    /**
     * @var bool
     */
    protected $hidden = true;
}

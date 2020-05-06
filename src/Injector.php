<?php

namespace BangNokia\ServeLiveReload;

use BangNokia\ServeLiveReload\Commands\ServeWebSocketsCommand;

class Injector
{
    /**
     * @param  string  $content
     * @return string
     */
    public function injectScripts($content)
    {
        $content .= (string)view('serve_livereload::script', [
            'host' => '127.0.0.1',
            'port' => ServeWebSocketsCommand::port()
        ]);

        return $content;
    }
}
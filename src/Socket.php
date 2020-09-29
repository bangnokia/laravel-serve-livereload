<?php

namespace BangNokia\ServeLiveReload;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Socket implements MessageComponentInterface
{
    public static $clients;

    public function __construct()
    {
        self::$clients = new \SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        self::$clients->attach($conn);
    }

    public function onClose(ConnectionInterface $conn)
    {
        self::$clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
    }
}

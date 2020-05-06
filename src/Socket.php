<?php


namespace BangNokia\ServeLiveReload;


use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class Socket implements MessageComponentInterface
{
    public static $clients;

    public function __construct()
    {
        self::$clients = new \SplObjectStorage;
    }

    function onOpen(ConnectionInterface $conn)
    {
        self::$clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        self::$clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
    }
}
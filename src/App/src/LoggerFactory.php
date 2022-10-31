<?php

namespace App;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggerFactory
{
    public function __invoke(ContainerInterface $container) : LoggerInterface
    {
        $config = $container->get('config');

        $log = new Logger($config['logger']['name']);

        $log->pushHandler(
            new StreamHandler(
                $config['logger']['directory'],
                Level::Debug
            )
        );

        return $log;
    }
}
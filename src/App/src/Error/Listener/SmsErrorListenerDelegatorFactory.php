<?php

declare(strict_types=1);

namespace App\Error\Listener;

use Laminas\Stratigility\Middleware\ErrorHandler;
use Psr\Container\ContainerInterface;

class SmsErrorListenerDelegatorFactory
{
    public function __invoke(ContainerInterface $container, string $name, callable $callback) : ErrorHandler
    {
        $errorHandler = $callback();
        $errorHandler->attachListener(
            $container->get(SmsErrorListener::class)
        );

        return $errorHandler;
    }
}
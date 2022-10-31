<?php
declare(strict_types=1);

use Laminas\Stratigility\Middleware\ErrorHandler;
use Mezzio\Container;
use Mezzio\Middleware;

return [
    'dependencies' => [
        'factories' => [
            ErrorHandler::class => Container\ErrorHandlerFactory::class,
            Middleware\ErrorResponseGenerator::class => Container\ErrorResponseGeneratorFactory::class,
        ],
    ],
];
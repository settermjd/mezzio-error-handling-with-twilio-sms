<?php

declare(strict_types=1);

namespace App\Error\Listener;

use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;

class SmsErrorListenerFactory
{
    public function __invoke(ContainerInterface $container) : SmsErrorListener
    {
        $config = $container->get('config');

        return new SmsErrorListener(
            $config['app']['name'],
            new Client(
                $config['twilio']['account_sid'],
                $config['twilio']['auth_token']
            ),
            $container->get(LoggerInterface::class),
            $config['sms-notification']['recipient_number'],
            $config['twilio']['send_from']
        );
    }
}

<?php

declare(strict_types=1);

namespace App\Error\Listener;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SmsErrorListener
{
    private Client $client;
    private LoggerInterface $logger;
    private string $to;
    private string $from;
    private string $appName;

    public function __construct(
        string $appName,
        Client $client,
        LoggerInterface $logger,
        string $to,
        string $from
    ) {
        $this->client = $client;
        $this->logger = $logger;
        $this->to = $to;
        $this->from = $from;
        $this->appName = $appName;
    }

    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response)
    {
        $template = <<<EOF
Exception in %s. 

Here are the specifics:
- Status code: %d.
- HTTP Method: %s.
- URI: %s.
- Error Message: %s.
- Occurred in file: "%s" on Line: %d
EOF;

        $message = sprintf(
            $template,
            $this->appName,
            $response->getStatusCode(),
            $request->getMethod(),
            (string) $request->getUri(),
            $error->getMessage(),
            $error->getFile(),
            $error->getLine(),
        );

        try {
            $this->client
                ->messages
                ->create(
                    $this->to,
                    [
                        "body" => $message,
                        "from" => $this->from
                    ]
                );
            $this->logger->info(
                sprintf('Error message sent to %s', $this->to),
                [
                    'error' => [
                        'message' => $error->getMessage(),
                        'file' => $error->getFile(),
                        'line' => $error->getLine(),
                    ],
                    'method' => $request->getMethod(),
                    'status' => $response->getStatusCode(),
                    'uri' => (string) $request->getUri(),
                ]
            );
        } catch (TwilioException $e) {
            $this->logger
                ->error(
                    sprintf(
                        'Could not send SMS notification. Twilio replied with: %s',
                        $e->getMessage()
                    )
                );
        }
    }
}
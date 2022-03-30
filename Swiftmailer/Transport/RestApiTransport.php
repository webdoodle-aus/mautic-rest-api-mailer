<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\Swiftmailer\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use MauticPlugin\MauticRestApiMailerBundle\Swiftmailer\MailDTOConvertor;
use Swift_Events_EventListener;
use Swift_Mime_SimpleMessage;

class RestApiTransport implements \Swift_Transport
{
    protected bool $started = false;

    private Client $client;
    private MailDTOConvertor $mailDTOConvertor;
    protected ?string $host;
    protected ?string $username;
    protected ?string $password;

    public function __construct(
        Client $client,
        MailDTOConvertor $mailDTOConvertor,
        ?string $host,
        ?string $username,
        ?string $password)
    {
        $this->host             = $host;
        $this->username         = $username;
        $this->password         = $password;
        $this->client           = $client;
        $this->mailDTOConvertor = $mailDTOConvertor;
    }

    public function isStarted()
    {
        return $this->started;
    }

    public function start()
    {
        $this->started = true;
    }

    public function stop()
    {
    }

    public function ping()
    {
        return true;
    }

    public function send(Swift_Mime_SimpleMessage $message, &$failedRecipients = null)
    {
        $dto = $this->mailDTOConvertor->toMailDTO($message);

        try {
            $this->client->post(
                $this->host,
                [
                    'auth' => [
                        $this->username, $this->password,
                    ],
                    'json' => $dto,
                ]
            );
        } catch (GuzzleException $exception) {
            throw new \Swift_TransportException($exception->getMessage());
        }

        return count($message->getTo());
    }

    public function registerPlugin(Swift_Events_EventListener $plugin)
    {
    }
}

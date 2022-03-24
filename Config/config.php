<?php
return [
    'name'        => 'Rest API Mailer Extension',
    'description' => 'This is a Mautic Extension that supports a new Email Configuration where Email are sent to a REST API.',
    'version'     => '1.0',
    'author'      => 'WebDoodle',
    'routes'      => [],
    'services'    => [
        'other'     => [
            'mautic_integration.rest_api.dto.convertor' => [
                'class' => \MauticPlugin\MauticRestApiMailerBundle\Swiftmailer\MailDTOConvertor::class
            ],
            'mautic_integration.rest_api.guzzle.client' => [
                'class' => 'GuzzleHttp\Client',
            ],
            'mautic.transport.rest_api' => [
                'class'        => \MauticPlugin\MauticRestApiMailerBundle\Swiftmailer\Transport\RestApiTransport::class,
                'arguments'    => [
                    'mautic_integration.rest_api.guzzle.client',
                    'mautic_integration.rest_api.dto.convertor',
                    '%mautic.mailer_host%',
                    '%mautic.mailer_user%',
                    '%mautic.mailer_password%'
                ],
                'tag'          => 'mautic.email_transport',
                'tagArguments' => [
                    \Mautic\EmailBundle\Model\TransportType::TRANSPORT_ALIAS => 'mautic.email.config.mailer_transport.rest_api',
                    \Mautic\EmailBundle\Model\TransportType::FIELD_HOST      => true,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_PORT      => false,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_API_KEY   => false,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_USER      => true,
                    \Mautic\EmailBundle\Model\TransportType::FIELD_PASSWORD  => true,
                ],
            ]
        ],
    ]
];
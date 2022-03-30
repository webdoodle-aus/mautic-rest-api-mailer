<?php

return [
    'name'        => 'Rest API Mailer Extension',
    'description' => 'This is a Mautic Extension that supports a new Email Configuration where Email are sent to a REST API.',
    'version'     => '1.0',
    'author'      => 'WebDoodle',
    'routes'      => [],
    'services'    => [
        'integrations' => [
            'mautic.integration.restapimailer' => [
                'class'     => \MauticPlugin\MauticRestApiMailerBundle\Integration\RestApiMailerIntegration::class,
                'arguments' => [
                    'event_dispatcher',
                    'mautic.helper.cache_storage',
                    'doctrine.orm.entity_manager',
                    'session',
                    'request_stack',
                    'router',
                    'translator',
                    'logger',
                    'mautic.helper.encryption',
                    'mautic.lead.model.lead',
                    'mautic.lead.model.company',
                    'mautic.helper.paths',
                    'mautic.core.model.notification',
                    'mautic.lead.model.field',
                    'mautic.plugin.model.integration_entity',
                    'mautic.lead.model.dnc',
                ],
            ],
        ],
        'other'     => [
            'mautic_integration.rest_api.dto.convertor' => [
                'class'     => \MauticPlugin\MauticRestApiMailerBundle\Swiftmailer\MailDTOConvertor::class,
                'arguments' => [
                    '@mautic.helper.plain_text_message',
                    '@mautic.helper.integration',
                ],
            ],
            'mautic_integration.rest_api.guzzle.client' => [
                'class' => 'GuzzleHttp\Client',
            ],
            'mautic.transport.rest_api' => [
                'class'        => \MauticPlugin\MauticRestApiMailerBundle\Swiftmailer\Transport\RestApiTransport::class,
                'arguments'    => [
                    'mautic_integration.rest_api.guzzle.client',
                    '@mautic_integration.rest_api.dto.convertor',
                    '%mautic.mailer_host%',
                    '%mautic.mailer_user%',
                    '%mautic.mailer_password%',
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
            ],
        ],
    ],
];

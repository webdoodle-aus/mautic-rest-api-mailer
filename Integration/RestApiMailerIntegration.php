<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\Integration;

use Mautic\CoreBundle\Form\Type\SortableListType;
use Mautic\PluginBundle\Integration\AbstractIntegration;

class RestApiMailerIntegration extends AbstractIntegration
{
    public const NAME         = 'RestApiMailer';
    public const DISPLAY_NAME = 'REST API Mailer';

    public function getName()
    {
        return self::NAME;
    }

    public function getDisplayName()
    {
        return self::DISPLAY_NAME;
    }

    public function getAuthenticationType()
    {
        return 'none';
    }

    public function appendToForm(&$builder, $data, $formArea)
    {
        $builder->add(
            'custom_body_properties',
            SortableListType::class,
            [
                'required'        => false,
                'label'           => 'mautic.rest_api_mailer.custom_properties',
                'attr'            => [
                    'tooltip'  => 'mautic.rest_api_mailer.custom_properties.tooltip',
                ],
                'option_required' => false,
                'with_labels'     => true,
                'key_value_pairs' => true, // do not store under a `list` key and use label as the key
            ]
        );
    }
}

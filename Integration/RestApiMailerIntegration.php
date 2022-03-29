<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\Integration;

use Mautic\PluginBundle\Integration\AbstractIntegration;

class RestApiMailerIntegration extends AbstractIntegration
{
    public function getName()
    {
        return 'RestApiMailer';
    }

    public function getAuthenticationType()
    {
        return 'none';
    }
}

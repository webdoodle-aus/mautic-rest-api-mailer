<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\Swiftmailer;

use MauticPlugin\MauticRestApiMailerBundle\DTO\MailDTO;

class MailDTOConvertor
{
    public function toMailDTO(\Swift_Mime_SimpleMessage $message): MailDTO
    {
        $dto = new MailDTO();


        return $dto;
    }
}
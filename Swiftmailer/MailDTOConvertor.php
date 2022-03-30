<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\Swiftmailer;

use Mautic\EmailBundle\Helper\PlainTextMessageHelper;
use Mautic\EmailBundle\Swiftmailer\Message\MauticMessage;
use Mautic\PluginBundle\Helper\IntegrationHelper;
use MauticPlugin\MauticRestApiMailerBundle\DTO\AttachmentDTO;
use MauticPlugin\MauticRestApiMailerBundle\DTO\ContentDTO;
use MauticPlugin\MauticRestApiMailerBundle\DTO\HeaderDTO;
use MauticPlugin\MauticRestApiMailerBundle\DTO\MailDTO;
use MauticPlugin\MauticRestApiMailerBundle\Integration\RestApiMailerIntegration;

class MailDTOConvertor
{
    private PlainTextMessageHelper $plainTextMessageHelper;
    private IntegrationHelper $integrationHelper;

    public function __construct(PlainTextMessageHelper $plainTextMessageHelper, IntegrationHelper $integrationHelper)
    {
        $this->plainTextMessageHelper = $plainTextMessageHelper;
        $this->integrationHelper      = $integrationHelper;
    }

    public function toMailDTO(\Swift_Mime_SimpleMessage $message): MailDTO
    {
        $dto              = new MailDTO();
        $dto->from        = $message->getFrom();
        $dto->to          = $message->getTo();
        $dto->cc          = $message->getCc();
        $dto->bcc         = $message->getBcc();
        $dto->replyTo     = $message->getReplyTo();
        $dto->subject     = $message->getSubject();
        $dto->contents    = $this->getContents($message);
        $dto->attachments = $this->getAttachments($message);
        $dto->metadata    = $this->getMetadata($message);
        $dto->headers     = $this->getHeaders($message);

        $integration = $this->integrationHelper->getIntegrationObject(RestApiMailerIntegration::NAME);
        if ($integration) {
            $settings = $integration->getIntegrationSettings()->getFeatureSettings();
            if (array_key_exists('custom_body_properties', $settings) && !empty($settings['custom_body_properties'])) {
                $dto->additionalProperties = $settings['custom_body_properties'];
            }
        }

        return $dto;
    }

    public function getHeaders(\Swift_Mime_SimpleMessage $message): array
    {
        $headers   = [];
        $headerSet = $message->getHeaders();

        foreach ($headerSet->listAll() as $headerName) {
            $header = $headerSet->get($headerName);
            if (!$header) {
                continue;
            }
            $headers[$headerName] = new HeaderDTO(
                $header->getFieldName(),
                $header->getFieldType(),
                $header->getFieldBody()
            );
        }

        return $headers;
    }

    private function getMetadata(\Swift_Mime_SimpleMessage $message): array
    {
        if (!$message instanceof MauticMessage || !$message->getMetadata()) {
            return [];
        }

        return $message->getMetadata();
    }

    private function getContents(\Swift_Mime_SimpleMessage $message): array
    {
        $content       = new ContentDTO($this->getContentType($message), $message->getBody());
        $contentSecond = null;

        // Plain text message must be first if present
        if ('text/plain' !== $content->type) {
            $plainText = $this->plainTextMessageHelper->getPlainTextFromMessageNotStatic($message);
            if ($plainText) {
                $contentSecond = $content;
                $content       = new ContentDTO('text/plain', $plainText);
            }
        }

        $contentsArray = [$content];
        if ($contentSecond) {
            $contentsArray[] = $contentSecond;
        }

        return $contentsArray;
    }

    private function getAttachments(\Swift_Mime_SimpleMessage $message): array
    {
        if (!$message instanceof MauticMessage || !$message->getAttachments()) {
            return [];
        }

        $attachments = [];
        foreach ($message->getAttachments() as $emailAttachment) {
            $fileContent = @file_get_contents($emailAttachment['filePath']);
            if (false === $fileContent) {
                continue;
            }

            $attachments[] = new AttachmentDTO(
                base64_encode($fileContent),
                $emailAttachment['contentType'],
                $emailAttachment['fileName']
            );
        }

        return $attachments;
    }

    private function getContentType(\Swift_Mime_SimpleMessage $message): string
    {
        return 'text/plain' === $message->getContentType() ? $message->getContentType() : 'text/html';
    }
}

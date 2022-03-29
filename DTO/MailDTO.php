<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\DTO;

class MailDTO implements \JsonSerializable
{
    public $from;
    public $to;
    public $cc;
    public $bcc;
    public $subject;
    public $contents;
    public $attachments;
    public $replyTo;
    public $headers;
    public $metadata;

    public function jsonSerialize()
    {
        return array_filter(
            [
                'from'              => $this->from,
                'to'                => $this->to,
                'cc'                => $this->cc,
                'bcc'               => $this->bcc,
                'subject'           => $this->subject,
                'content'           => $this->contents,
                'attachments'       => $this->attachments,
                'headers'           => $this->headers,
                'reply_to'          => $this->replyTo,
                'metadata'          => $this->metadata,
            ],
            function ($value) {
                return null !== $value;
            }
        ) ?: null;
    }
}

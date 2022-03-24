<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\DTO;

class MailDTO implements \JsonSerializable
{
    public $from;
    public $to;
    public $subject;
    public $contents;
    public $attachments;
    public $replyTo;
    public $headers;
    public $sections;

    public function jsonSerialize()
    {
        return array_filter(
            [
                'from'              => $this->from,
                'to'                => $this->to,
                'subject'           => $this->subject,
                'content'           => $this->contents,
                'attachments'       => $this->attachments,
                'sections'          => $this->sections,
                'headers'           => $this->headers,
                'reply_to'          => $this->replyTo
            ],
            function ($value) {
                return $value !== null;
            }
        ) ?: null;
    }
}
<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\DTO;

class AttachmentDTO implements \JsonSerializable
{
    public string $content;
    public string $type;
    public string $filename;

    public function __construct(string $content, string $type, string $filename)
    {
        $this->content  = $content;
        $this->type     = $type;
        $this->filename = $filename;
    }

    public function jsonSerialize()
    {
        return array_filter(
            [
                'content'  => $this->content,
                'type'     => $this->type,
                'filename' => $this->filename,
            ],
            function ($value) {
                return null !== $value;
            }
        ) ?: null;
    }
}

<?php

declare(strict_types=1);

namespace MauticPlugin\MauticRestApiMailerBundle\DTO;

class ContentDTO implements \JsonSerializable
{
    public string $type;
    public string $value;

    public function __construct(string $type, string $value)
    {
        $this->type  = $type;
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return array_filter(
            [
                'type'  => $this->type,
                'value' => $this->value,
            ],
            function ($value) {
                return null !== $value;
            }
        ) ?: null;
    }
}

<?php

namespace MauticPlugin\MauticRestApiMailerBundle\DTO;

class HeaderDTO implements \JsonSerializable
{
    public string $name;
    public int $type;
    public string $value;

    public function __construct(string $name, int $type, string $value)
    {
        $this->name  = $name;
        $this->type  = $type;
        $this->value = $value;
    }

    public function jsonSerialize()
    {
        return array_filter(
            [
                'name'  => $this->name,
                'type'  => $this->type,
                'value' => $this->value,
            ],
            function ($value) {
                return null !== $value;
            }
        ) ?: null;
    }
}

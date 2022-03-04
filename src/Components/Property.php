<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components;

final class Property
{
    private string $name;
    private string $type;

    private function __construct()
    {
    }

    public static function new(
        string $name,
        string $type
    ): Property {
        $self = new self();

        $self->name = $name;
        $self->type = $type;

        return $self;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function sameAs(Property $property): bool
    {
        return $property->getName() === $this->getName()
            && $property->getType() === $property->getType();
    }
}

<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components;

use InvalidArgumentException;

final class Entity
{
    private string $class;
    private PropertyCollection $properties;

    private function __construct()
    {
    }

    public static function new(
        string $class,
        PropertyCollection $properties
    ): Entity {
        $self = new self();

        $self->class = $class;
        $self->properties = $properties;

        return $self;
    }

    public function merge(Entity $entity): void
    {
        if ($entity->getClass() !== $this->getClass()) {
            throw new InvalidArgumentException("Trying to merge Entity set as different class.");
        }

        $this->properties->mergeProperties($entity->getProperties());
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getProperties(): PropertyCollection
    {
        return $this->properties;
    }
}

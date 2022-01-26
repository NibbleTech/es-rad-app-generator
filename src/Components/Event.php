<?php

declare(strict_types=1);

namespace EsRadAppGenerator\Components;

use InvalidArgumentException;

final class Event
{
    private string $class;
    private PropertyCollection $properties;

    private function __construct()
    {
    }

    public static function new(
        string $class,
        ?PropertyCollection $propertyCollection = null
    ): Event {
        $self = new self();

        $self->class = $class;
        $self->properties = $propertyCollection ?? PropertyCollection::with([]);

        return $self;
    }

    public function merge(Event $event): void
    {
        if ($event->getClass() !== $this->getClass()) {
            throw new InvalidArgumentException("Trying to merge Event set as different class.");
        }

        $this->properties->mergeProperties($event->getProperties());
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

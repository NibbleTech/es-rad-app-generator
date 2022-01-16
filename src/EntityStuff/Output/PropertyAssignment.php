<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

class PropertyAssignment
{
    /**
     * @var Property
     */
    private $entityProperty;
    /**
     * @var Property
     */
    private $eventProperty;

    final private function __construct()
    {
    }

    public static function with(
        Property $entityProperty,
        Property $eventProperty
    ): PropertyAssignment {
        $self = new static();

        $self->entityProperty = $entityProperty;
        $self->eventProperty  = $eventProperty;

        return $self;
    }

    public function getEntityProperty(): Property
    {
        return $this->entityProperty;
    }

    public function getEventProperty(): Property
    {
        return $this->eventProperty;
    }
}

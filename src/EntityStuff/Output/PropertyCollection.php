<?php

declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

class PropertyCollection
{
    /**
     * @var Property[]
     */
    private array $properties = [];

    private function __construct()
    {
    }

    /**
     * @param Property[] $properties
     */
    public static function with(array $properties): PropertyCollection
    {
        $self = new static();

        foreach ($properties as $property) {
            $self->properties[$property->getName()] = $property;
        }

        return $self;
    }

    public function mergeProperties(PropertyCollection $propertyCollection): void
    {
        foreach ($propertyCollection->getProperties() as $property) {
            if (isset($this->properties[$property->getName()])) {
                continue;
            }

            $this->properties[$property->getName()] = $property;
        }
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}

<?php

declare(strict_types=1);

namespace EsRadAppGenerator\Components;

final class PropertyCollection
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
        $self = new self();

        foreach ($properties as $property) {
            $self->addProperty($property);
        }

        return $self;
    }

    public function mergeProperties(PropertyCollection $propertyCollection): void
    {
        foreach ($propertyCollection->getProperties() as $property) {
            $this->addProperty($property);
        }
    }

    /**
     * @return Property[]
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getProperty(string $name): Property
    {
        if ($this->hasProperty($name) === false) {
            throw new \RuntimeException("Property collection does not have property $name");
        }

        return $this->properties[$name];
    }

    public function hasProperty(string $name): bool
    {
        foreach ($this->properties as $property) {
            if ($property->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    public function addProperty(Property $newProperty): void
    {
        if ($this->hasProperty($newProperty->getName())) {
            /**
             * Has a property of the same name already, need to check the types.
             * If same type we can ignore addition.
             * If different type we throw an error as we cant add the same property
             * with 2 different type definitions.
             */

            $existingProperty = $this->getProperty($newProperty->getName());

            if ($existingProperty->sameAs($newProperty)) {
                return;
            }
        }

        $this->properties[$newProperty->getName()] = $newProperty;
    }
}

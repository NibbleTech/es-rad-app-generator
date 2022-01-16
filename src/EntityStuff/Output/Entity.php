<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

class Entity
{
    /**
     * @var string
     */
    private $class;
    /**
     * @var PropertyCollection
     */
    private $properties;

    final private function __construct()
    {
    }
    
    public static function new(
        string $class,
        PropertyCollection $properties
    ): Entity {
        $self = new static();
        
        $self->class = $class;
        $self->properties = $properties;
        
        return $self;
    }

    public function merge(Entity $entity): void
    {
        if ($entity->getClass() !== $this->getClass()) {
            throw new \InvalidArgumentException("Trying to merge Entity set as different class.");
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

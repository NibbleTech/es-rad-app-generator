<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

class Event
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
        ?PropertyCollection $propertyCollection = null
    ): Event {
        $self = new static();
        
        $self->class = $class;
        $self->properties = $propertyCollection ?? PropertyCollection::with([]);
        
        return $self;
    }
    
    public function merge(Event $event): void
    {
        if ($event->getClass() !== $this->getClass()) {
            throw new \InvalidArgumentException("Trying to merge Event set as different class.");
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

<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

use EsRadAppGenerator\EntityStuff\Output\SideEffects\SideEffect;

class Instruction
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var Event
     */
    private $event;
    /**
     * @var Entity[]
     */
    private $entities = [];
    /**
     * @var SideEffect[]
     */
    private $sideEffects = [];

    final private function __construct()
    {
    }

    /**
     * @param string                 $name
     * @param Event                  $event
     * @param Entity[]               $entities
     * @param SideEffect[] $sideEffects
     *
     * @return Instruction
     */
    public static function new(
        string $name,
        Event $event,
        array $entities,
        array $sideEffects
    ): Instruction {
        $self = new static();

        $self->name        = $name;
        $self->event       = $event;
        $self->entities    = $entities;
        $self->sideEffects = $sideEffects;

        return $self;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getListenerName(): string
    {
        return str_replace(' ', '', ucwords($this->name));
    }

    public function getEvent(): Event
    {
        return $this->event;
    }

    /**
     * @return Entity[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @return SideEffect[]
     */
    public function getSideEffects(): array
    {
        return $this->sideEffects;
    }
}

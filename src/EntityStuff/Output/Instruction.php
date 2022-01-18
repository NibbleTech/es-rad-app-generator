<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

use EsRadAppGenerator\EntityStuff\Output\SideEffects\SideEffect;

class Instruction
{
    private string $name;
    private Event $event;
    /**
     * @var Entity[]
     */
    private array $entities = [];
    /**
     * @var SideEffect[]
     */
    private array $sideEffects = [];

    private function __construct()
    {
    }

    /**
     * @param Entity[]               $entities
     * @param SideEffect[] $sideEffects
     *
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

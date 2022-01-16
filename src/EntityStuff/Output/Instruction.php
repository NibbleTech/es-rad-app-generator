<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

class Instruction
{
    /**
     * @var string
     */
    private $filepath;
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
     * @param string       $filepath
     * @param string       $name
     * @param Event        $event
     * @param Entity[]     $entities
     * @param SideEffect[] $sideEffects
     *
     * @return Instruction
     */
    public static function new(
        string $filepath,
        string $name,
        Event $event,
        array $entities,
        array $sideEffects
    ): Instruction {
        $self = new static();

        $self->filepath    = $filepath;
        $self->name        = $name;
        $self->event       = $event;
        $self->entities    = $entities;
        $self->sideEffects = $sideEffects;

        return $self;
    }

    public function getFilepath(): string
    {
        return $this->filepath;
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

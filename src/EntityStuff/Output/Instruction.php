<?php

declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

use EsRadAppGenerator\EntityStuff\Output\SideEffects\SideEffect;

final class Instruction
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
     * @param SideEffect[] $sideEffects
     */
    public static function new(
        string $name,
        Event $event,
        array $sideEffects
    ): Instruction {
        $self = new self();

        $self->name        = $name;
        $self->event       = $event;
        $self->sideEffects = $sideEffects;
        $self->generateEntities();

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

    private function generateEntities(): void
    {
        /** @var array<string, PropertyCollection> $entitiesDrafts */
        $entitiesDrafts = [];

        foreach ($this->sideEffects as $sideEffect) {
            if (isset($entitiesDrafts[$sideEffect->getEntityClass()])) {
                $propertyCollection = $entitiesDrafts[$sideEffect->getEntityClass()];
            } else {
                $propertyCollection = PropertyCollection::with([]);
            }

            foreach ($sideEffect->getPropertyMappings() as $propertyMapping) {
                $propertyCollection->addProperty($propertyMapping->getEntityProperty());
                /**
                 * @TODO add try catch here once addProperty uses custom exception for conflicting property types
                 */
            }

            $entitiesDrafts[$sideEffect->getEntityClass()] = $propertyCollection;
        }

        foreach ($entitiesDrafts as $entityClass => $entityProperties) {
            $this->entities[] = Entity::new(
                $entityClass,
                $entityProperties,
            );
        }
    }
}

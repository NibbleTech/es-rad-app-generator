<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components;

use InvalidArgumentException;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\SideEffect;

final class Entity
{
	private string $class;
	private PropertyCollection $properties;
	/**
	 * @var Event[]
	 */
	private array $appliesEvents = [];

	private function __construct()
	{
	}

	public static function new(
		string $class,
		/* @deprecated */ 
		PropertyCollection $properties = null
	): Entity {
		$self = new self();

		$self->class      = $class;
		$self->properties = $properties ?? PropertyCollection::with([]);

		return $self;
	}

	/**
	 * @param Event        $event
	 * @param SideEffect[] $sideEffects
	 *
	 * @return $this
	 */
	public function appliesEvent(
		Event $event,
		array $sideEffects = []
	): self {
		if (!empty($this->appliesEvents[$event->getClass()])) {
			throw new InvalidArgumentException("Event already in applies list.");
		}
		$this->appliesEvents[$event->getClass()] = $event;
		foreach ($sideEffects as $sideEffect) {
			$this->properties = $this->calculatePropertiesFromSideEffects($sideEffect);
		}

		return $this;
	}

	private function calculatePropertiesFromSideEffects(SideEffect $sideEffect): PropertyCollection
	{
		$propertyCollection = clone $this->properties;

		foreach ($sideEffect->getPropertyMappings() as $propertyMapping) {
			$propertyCollection->addProperty($propertyMapping->getEntityProperty());
			/**
			 * @TODO add try catch here once addProperty uses custom exception for conflicting property types
			 */
		}

		return $propertyCollection;
	}

	public function merge(Entity $entity): void
	{
		if ($entity->getClass() !== $this->getClass()) {
			throw new InvalidArgumentException("Trying to merge Entity set as different class.");
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

	public function getAppliesEvents(): array
	{
		return $this->appliesEvents;
	}
}

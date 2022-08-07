<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator;

use NibbleTech\EsRadAppGenerator\Components\Entity;
use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\EventConsumption;

/**
 * Mutable state for compiling all different components into a domain.
 */
final class DomainBuilder
{
	public function __construct(
		private array $events = [],
		private array $entities = [],
		private array $eventConsumers = [],
	) {
	}
	
	public function addEvent(Event $event): void
	{
	    $this->events[$event->getClass()] = $event;
	}
	
	public function addEntity(Entity $entity): void
	{
	    $this->entities[$entity->getClass()] = $entity;
	}

	public function addEventConsumer(EventConsumption $eventConsumption): void
	{
	    $this->eventConsumers[$eventConsumption->getListenerName()] = $eventConsumption;
	}

	/**
	 * Provide mutable final map of a Domain.
	 *
	 * @return Domain
	 */
	public function getImmutableDomain(): Domain
	{
		return new Domain(
			$this->events,
			$this->entities,
			$this->eventConsumers
		);
	}
}

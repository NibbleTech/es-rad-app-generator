<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator;

final class Domain
{
	public function __construct(
		private array $events,
		private array $entities,
		private array $eventConsumers,
	) {
	}

	public function getEvents(): array
	{
		return $this->events;
	}

	public function getEntities(): array
	{
		return $this->entities;
	}

	public function getEventConsumers(): array
	{
		return $this->eventConsumers;
	}
}

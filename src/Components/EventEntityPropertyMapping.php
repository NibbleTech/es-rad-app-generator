<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components;

final class EventEntityPropertyMapping
{
	private Property $entityProperty;
	private Property $eventProperty;

	private function __construct()
	{
	}

	public static function with(
		Property $entityProperty,
		Property $eventProperty
	): EventEntityPropertyMapping {
		$self = new self();

		$self->entityProperty = $entityProperty;
		$self->eventProperty  = $eventProperty;

		return $self;
	}

	public function getEntityProperty(): Property
	{
		return $this->entityProperty;
	}

	public function getEventProperty(): Property
	{
		return $this->eventProperty;
	}
}

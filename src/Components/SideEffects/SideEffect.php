<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components\SideEffects;

use NibbleTech\EsRadAppGenerator\Components\EventEntityPropertyMapping;

interface SideEffect
{
	public function getEntityClass(): string;

	/**
	 * @return EventEntityPropertyMapping[]
	 */
	public function getPropertyMappings(): array;
}

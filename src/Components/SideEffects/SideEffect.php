<?php

declare(strict_types=1);

namespace EsRadAppGenerator\Components\SideEffects;

use EsRadAppGenerator\Components\EventEntityPropertyMapping;

interface SideEffect
{
    public function getEntityClass(): string;

    /**
     * @return EventEntityPropertyMapping[]
     */
    public function getPropertyMappings(): array;
}

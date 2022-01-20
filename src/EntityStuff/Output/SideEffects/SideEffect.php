<?php

declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output\SideEffects;

use EsRadAppGenerator\EntityStuff\Output\EventEntityPropertyMapping;

interface SideEffect
{
    public function getEntityClass(): string;

    /**
     * @return EventEntityPropertyMapping[]
     */
    public function getPropertyMappings(): array;
}

<?php

declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output\SideEffects;

interface SideEffect
{
    public function getEntityClass(): string;

    public function getPropertyMappings(): array;
}
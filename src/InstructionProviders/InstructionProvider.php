<?php

declare(strict_types=1);

namespace EsRadAppGenerator\InstructionProviders;

use EsRadAppGenerator\EntityStuff\Output\Instruction;

interface InstructionProvider
{
    /**
     * @return Instruction[]
     */
    public function provideInstructions(): array;
}

<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use Nette\PhpGenerator\Printer;

final class CustomNettePrinter extends Printer
{
    public string $indentation = "    ";
    public int $linesBetweenProperties = 0;
    public int $linesBetweenMethods = 1;
    public string $returnTypeColon = ': ';
}

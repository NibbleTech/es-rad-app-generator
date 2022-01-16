<?php
declare(strict_types=1);

namespace EsRadAppGeneratorGenerator\CodeGenerator;

use Nette\PhpGenerator\Printer;

class CustomNettePrinter extends Printer
{
    protected $indentation = "    ";
    protected $linesBetweenProperties = 0;
    protected $linesBetweenMethods = 1;
    protected $returnTypeColon = ': ';
}

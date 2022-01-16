<?php
declare(strict_types=1);

namespace EsRadAppGeneratorGenerator\CodeGenerator\Common;

use EsRadAppGeneratorGenerator\CodeGenerator\CustomNettePrinter;
use Nette\PhpGenerator\PhpFile;

class EntityInterfaceGenerator
{
    final public function __construct()
    {
    }

    public function generate(): string
    {
        $file = new PhpFile();
        $file
            ->setStrictTypes();

        $namespace = $file->addNamespace('App\Common');

        $class = $namespace->addInterface('Entity');
        
        return (new CustomNettePrinter())->printFile($file);
    }
}

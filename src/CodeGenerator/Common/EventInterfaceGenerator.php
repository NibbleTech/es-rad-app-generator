<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator\Common;

use EsRadAppGenerator\CodeGenerator\CustomNettePrinter;
use Nette\PhpGenerator\PhpFile;

class EventInterfaceGenerator
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

        $namespace->addInterface('Event');
        
        return (new CustomNettePrinter())->printFile($file);
    }
}

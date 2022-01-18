<?php

declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator\Common;

use EsRadAppGenerator\CodeGenerator\CustomNettePrinter;
use Nette\PhpGenerator\PhpFile;

final class EventListenerInterfaceGenerator
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

        $class = $namespace->addInterface('EventListener');

        $class
            ->addMethod('handle')
            ->setPublic();

        $handleMethod = $class
            ->addMethod('handle')
            ->setReturnType('void');

        $handleMethod
            ->addParameter('event')
            ->setType('App\Common\Event');

        return (new CustomNettePrinter())->printFile($file);
    }
}

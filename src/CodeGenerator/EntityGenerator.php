<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\Entity;
use Nette\PhpGenerator\PhpFile;

final class EntityGenerator
{
    final public function __construct()
    {
    }

    public function generate(Entity $entity): string
    {
        $file = new PhpFile();
        $file
            ->setStrictTypes();

        $namespace = $file->addNamespace('App\Entities');

        $namespace->addUse("App\Common\Entity");

        $class = $namespace->addClass($entity->getClass());

        $class
            ->addImplement("App\Common\Entity");

        foreach ($entity->getProperties()->getProperties() as $property) {
            $class->addProperty(
                $property->getName()
            )->setType(
                $property->getType()
            );
        }

        return (new CustomNettePrinter())->printFile($file);
    }
}

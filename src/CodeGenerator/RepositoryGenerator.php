<?php

declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\EntityStuff\Output\Entity;
use Nette\PhpGenerator\PhpFile;
use Ramsey\Uuid\UuidInterface;

final class RepositoryGenerator
{
    final public function __construct()
    {
    }

    public function generate(Entity $entity): string
    {
        $file = new PhpFile();
        $file
            ->setStrictTypes();

        $namespace = $file->addNamespace('App\Repositories');

        $entityFqcn = 'App\Entities\\' . $entity->getClass();

        $namespace->addUse($entityFqcn);
        $namespace->addUse(UuidInterface::class);

        $class = $namespace->addClass($entity->getClass() . 'Repository');

        $findMethod = $class->addMethod('find');
        $findMethod->setReturnType($entityFqcn);
        $findMethod
            ->addParameter('id')
            ->setType(UuidInterface::class);

        $deleteMethod = $class->addMethod('delete');
        $deleteMethod->setReturnType('void');
        $deleteMethod
            ->addParameter('entity')
            ->setType($entityFqcn);

        $persistMethod = $class->addMethod('persist');
        $persistMethod->setReturnType($entityFqcn);
        $persistMethod
            ->addParameter('entity')
            ->setType($entityFqcn);

        $persistMethod = $class->addMethod('findBy');
        $persistMethod->setReturnType($entityFqcn);
        $persistMethod->setReturnNullable(true);
        $persistMethod
            ->addParameter('properties')
            ->setType('array');

        return (new CustomNettePrinter())->printFile($file);
    }
}

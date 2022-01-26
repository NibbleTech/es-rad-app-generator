<?php

declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\Components\SideEffects\SideEffect;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\PhpNamespace;

/**
 * Adds all the relevant things to the Nette\PhpGenerator components given
 */
final class SideEffectCrudGenerationDecorator
{
    private SideEffectCrudCodeGenerator $handleMethodCodeGenerator;
    private PhpNamespace $namespace;
    private ClassType $class;
    private Method $constructorMethod;
    private Method $handleMethod;

    final public function __construct()
    {
    }

    public static function for(
        SideEffectCrudCodeGenerator $handleMethodCodeGenerator,
        PhpNamespace $namespace,
        ClassType $class,
        Method $constructorMethod,
        Method $handleMethod
    ): SideEffectCrudGenerationDecorator {
        $self = new self();

        $self->handleMethodCodeGenerator = $handleMethodCodeGenerator;
        $self->namespace = $namespace;
        $self->class = $class;
        $self->constructorMethod = $constructorMethod;
        $self->handleMethod = $handleMethod;

        return $self;
    }

    public function decorateWith(
        SideEffect $sideEffect
    ): void {
        $entityFqcn = 'App\Entities\\' . $sideEffect->getEntityClass();

        $this->namespace->addUse($entityFqcn);
        $entityRepository = 'App\Repositories\\' . $sideEffect->getEntityClass() . 'Repository';
        $this->namespace->addUse($entityRepository);

        $repositoryShortClass = $sideEffect->getEntityClass() . 'Repository';
        $repositoryPropertyName = lcfirst($repositoryShortClass);

        $this->constructorMethod->addParameter(lcfirst($repositoryShortClass))
            ->setType('App\Repositories\\' . $sideEffect->getEntityClass() . 'Repository');

        $this->constructorMethod->addBody(
            "\$this->{$repositoryPropertyName} = \${$repositoryPropertyName};"
        );

        $this->class->addProperty($repositoryPropertyName)
            ->setPrivate();

        $this->handleMethod->addBody(
            $this->handleMethodCodeGenerator->generate($sideEffect)
        );
    }
}

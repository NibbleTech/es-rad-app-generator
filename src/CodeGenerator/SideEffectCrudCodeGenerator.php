<?php

declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use InvalidArgumentException;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\SideEffect;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Creation;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Deletion;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Update;

/**
 * Generates CRUD-y code for side effects that go into an event listener
 */
class SideEffectCrudCodeGenerator
{
    final public function __construct()
    {
    }

    public function generate(SideEffect $sideEffect): string
    {
        return match ($sideEffect::class) {
            Creation::class => $this->createCode($sideEffect),
            Update::class => $this->updateCode($sideEffect),
            Deletion::class => $this->deleteCode($sideEffect),
            default => throw new InvalidArgumentException("Unsupported SideEffect action type."),
        };
    }

    private function createCode(Creation $sideEffect): string
    {
        $entityVariableName = $this->getEntityVariableName($sideEffect);
        $entityRepoShortName = $this->getEntityRepositoryShortClassName($sideEffect);

        $class = $sideEffect->getEntityClass();

        $code = "\$$entityVariableName = new $class();" . PHP_EOL;

        foreach ($sideEffect->getPropertyMappings() as $propertyMapping) {
            $code .= "\$$entityVariableName->{$propertyMapping->getEntityProperty()->getName()} = \$event->{$propertyMapping->getEventProperty()->getName()};" . PHP_EOL;
        }

        $code .= "\$this->{$entityRepoShortName}->persist(\$$entityVariableName);" . PHP_EOL;

        return $code;
    }

    private function updateCode(Update $sideEffect): string
    {
        $entityVariableName = $this->getEntityVariableName($sideEffect);
        $entityRepoShortName = $this->getEntityRepositoryShortClassName($sideEffect);

        $repoProperty = "\$this->" . lcfirst($sideEffect->getEntityClass()) . 'Repository';

        $array = "[" . PHP_EOL;
        foreach ($sideEffect->getPropertyMappings() as $propertyMapping) {
            $arrayItem = "\t'" . $propertyMapping->getEntityProperty()->getName() . "'" . ' => '
                . '$event->get' . ucfirst($propertyMapping->getEventProperty()->getName()) . '(),' . PHP_EOL;
            $array .= $arrayItem;
        }
        $array .= "]";

        $code = '';

        $code .= "\$$entityVariableName = " . $repoProperty . '->findBy(' . $array . ');' . PHP_EOL;

        foreach ($sideEffect->getPropertyMappings() as $propertyMapping) {
            $code .= "\$$entityVariableName->{$propertyMapping->getEntityProperty()->getName()} = \$event->{$propertyMapping->getEventProperty()->getName()};" . PHP_EOL;
        }

        $code .= "\$this->{$entityRepoShortName}->persist(\$$entityVariableName);" . PHP_EOL;

        return $code;
    }

    private function deleteCode(Deletion $sideEffect): string
    {
        $entityVariableName = $this->getEntityVariableName($sideEffect);
        $entityRepoShortName = $this->getEntityRepositoryShortClassName($sideEffect);

        $repoProperty = "\$this->" . $entityRepoShortName;

        $array = "[" . PHP_EOL;
        foreach ($sideEffect->getPropertyMappings() as $propertyMapping) {
            $arrayItem = "\t'" . $propertyMapping->getEntityProperty()->getName() . "'" . ' => '
                . '$event->get' . ucfirst($propertyMapping->getEventProperty()->getName()) . '(),' . PHP_EOL;
            $array .= $arrayItem;
        }
        $array .= "]";

        $code = '';

        $code .= "\$$entityVariableName = " . $repoProperty . '->findBy(' . $array . ');' . PHP_EOL;

        $code .= $repoProperty . "->delete(\$$entityVariableName);" . PHP_EOL;

        return $code;
    }

    public function getEntityVariableName(
        SideEffect $sideEffect
    ): string {
        $variableName = 'entity';

        $shortClass = array_reverse(explode('\\', $sideEffect->getEntityClass()))[0];
        $variableName .= $shortClass;

        switch ($sideEffect::class) {
            case Creation::class:
                $variableName .= 'ForCreation';
                break;
            case Update::class:
                $variableName .= 'ForUpdate';
                break;
            case Deletion::class:
                $variableName .= 'ForDeletion';
                break;
            default:
                throw new InvalidArgumentException("Unsupported SideEffect action type.");
        }

        return $variableName;
    }

    public function getEntityRepositoryShortClassName(
        SideEffect $sideEffect
    ): string {
        return lcfirst($sideEffect->getEntityClass()) . 'Repository';
    }
}

<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\EntityStuff\Output\SideEffect;
use EsRadAppGenerator\Lexer\Tokens\T_Crud_Action;

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
        switch ($sideEffect->getAction()) {
            case SideEffect::CREATE:
                return $this->createCode($sideEffect);
                break;
            case SideEffect::UPDATE:
                return $this->updateCode($sideEffect);
                break;
            case SideEffect::DELETE:
                return $this->deleteCode($sideEffect);
                break;
            default:
                throw new \InvalidArgumentException("Unsupported SideEffect action type.");
                break;
        }
    }

    private function createCode(SideEffect $sideEffect): string
    {
        $entityVariableName = $this->getEntityVariableName($sideEffect);
        $entityRepoShortName = $this->getEntityRepositoryShortClassName($sideEffect);
        
        $class = $sideEffect->getEntityClass();

        $code = "\$$entityVariableName = new $class();" . PHP_EOL;

        foreach ($sideEffect->getPropertyAssignments() as $propertyAssignment) {
            $code .= "\$$entityVariableName->{$propertyAssignment->getEntityProperty()->getName()} = \$event->{$propertyAssignment->getEventProperty()->getName()};" . PHP_EOL;
        }

        $code .= "\$this->{$entityRepoShortName}->persist(\$$entityVariableName);" . PHP_EOL;

        return $code;
    }

    private function updateCode(SideEffect $sideEffect): string
    {
        $entityVariableName = $this->getEntityVariableName($sideEffect);
        $entityRepoShortName = $this->getEntityRepositoryShortClassName($sideEffect);
        
        $repoProperty = "\$this->" . lcfirst($sideEffect->getEntityClass()) . 'Repository';

        $array = "[" . PHP_EOL;
        foreach ($sideEffect->getPropertyAssignments() as $propertyAssignment) {
            $arrayItem = "\t'" . $propertyAssignment->getEntityProperty()->getName() . "'" . ' => '
                . '$event->get' . ucfirst($propertyAssignment->getEventProperty()->getName()) . '(),' . PHP_EOL;
            $array .= $arrayItem;
        }
        $array .= "]";

        $code = '';

        $code .= "\$$entityVariableName = " . $repoProperty . '->findBy(' . $array . ');' . PHP_EOL;

        foreach ($sideEffect->getPropertyAssignments() as $propertyAssignment) {
            $code .= "\$$entityVariableName->{$propertyAssignment->getEntityProperty()->getName()} = \$event->{$propertyAssignment->getEventProperty()->getName()};" . PHP_EOL;
        }

        $code .= "\$this->{$entityRepoShortName}->persist(\$$entityVariableName);" . PHP_EOL;

        return $code;
    }

    private function deleteCode(SideEffect $sideEffect): string
    {
        $entityVariableName = $this->getEntityVariableName($sideEffect);
        $entityRepoShortName = $this->getEntityRepositoryShortClassName($sideEffect);
        
        $repoProperty = "\$this->" . $entityRepoShortName;

        $array = "[" . PHP_EOL;
        foreach ($sideEffect->getPropertyAssignments() as $propertyAssignment) {
            $arrayItem = "\t'" . $propertyAssignment->getEntityProperty()->getName() . "'" . ' => '
                . '$event->get' . ucfirst($propertyAssignment->getEventProperty()->getName()) . '(),' . PHP_EOL;
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
        
        switch ($sideEffect->getAction()) {
            case SideEffect::CREATE:
                $variableName .= 'ForCreation';
                break;
            case SideEffect::UPDATE:
                $variableName .= 'ForUpdate';
                break;
            case SideEffect::DELETE:
                $variableName .= 'ForDeletion';
                break;
            default:
                throw new \InvalidArgumentException("Unsupported SideEffect action type.");
        }
        
        return $variableName;
    }
    
    public function getEntityRepositoryShortClassName(
        SideEffect $sideEffect
    ): string {
        return lcfirst($sideEffect->getEntityClass()) . 'Repository';
    }

}

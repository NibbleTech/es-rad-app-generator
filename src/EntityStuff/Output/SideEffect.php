<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

use EsRadAppGenerator\Lexer\Tokens\T_Crud_Action;
use EsRadAppGenerator\Lexer\Tokens\T_Crud_Create;
use EsRadAppGenerator\Lexer\Tokens\T_Crud_Delete;
use EsRadAppGenerator\Lexer\Tokens\T_Crud_Update;

class SideEffect
{
    const CREATE = 1;
    const UPDATE = 2;
    const DELETE = 3;
    /**
     * @var int
     */
    private $action;
    /**
     * @var string
     */
    private $entityClass;
    /**
     * Array of property assignments keyed by 
     * @var PropertyAssignment[]
     */
    private $propertyAssignments;
    
    final private function __construct()
    {
    }
    
    public static function fromToken(T_Crud_Action $token): SideEffect
    {
        $self = new static();
        
        switch (get_class($token)) {
            case T_Crud_Create::class:
                $self->action = SideEffect::CREATE;
                break;
            case T_Crud_Update::class:
                $self->action = SideEffect::UPDATE;
                break;
            case T_Crud_Delete::class:
                $self->action = SideEffect::DELETE;
                break;
            default:
                throw new \InvalidArgumentException("Unsupported Token given.");
                break;
        }
        
        return $self;
    }
    
    public function onEntityClass(string $entity): void
    {
        $this->entityClass = $entity;
    }
    
    public function withValueAssignment(
        PropertyAssignment $propertyAssignment
    ): void {
        $this->propertyAssignments[] = $propertyAssignment;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getAction(): int
    {
        return $this->action;
    }

    /**
     * @return PropertyAssignment[]
     */
    public function getPropertyAssignments(): array
    {
        return $this->propertyAssignments;
    }
}

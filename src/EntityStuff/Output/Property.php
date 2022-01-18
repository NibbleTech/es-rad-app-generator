<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

class Property
{
    private string $name;
    private string $type;
    
    private function __construct()
    {
    }
    
    public static function new(
        string $name
    ): Property {
        $self = new static();
        
        $self->name = $name;
        
        return $self;
    }

    public function getName(): string
    {
        return $this->name;
    }
}

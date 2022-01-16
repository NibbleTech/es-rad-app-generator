<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\EntityStuff\Output\Event;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

class EventGenerator
{
    final public function __construct()
    {
    }
    
    public function generate(Event $event): string
    {
        $file = new PhpFile();
        $file
            ->setStrictTypes();
        
        $namespace = $file->addNamespace('App\Events');
        
        $namespace->addUse("App\Common\Event");
        
        $class = $namespace->addClass($event->getClass());
        
        $class
            ->addImplement("App\Common\Event");

        foreach ($event->getProperties()->getProperties() as $property) {
            $class->addProperty(
                $property->getName()
            );
        }
        
        return (new CustomNettePrinter()) ->printFile($file);
    }
}

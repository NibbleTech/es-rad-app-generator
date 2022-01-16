<?php
declare(strict_types=1);

namespace EsRadAppGeneratorGenerator\CodeGenerator;

class CodeGeneration
{
    private static $tab = "\t";
    
    final private function __construct()
    {
    }
    
    public static function tab(): string
    {
        return self::$tab;
    }
}

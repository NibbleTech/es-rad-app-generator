<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\Components\Entity;
use EsRadAppGenerator\Components\Property;
use EsRadAppGenerator\Components\PropertyCollection;
use PHPUnit\Framework\TestCase;

class EntityGeneratorTest extends TestCase
{
    private EntityGenerator $entityGenerator;
    
    protected function setUp(): void
    {
        $this->entityGenerator = new EntityGenerator();
    }

    function test_it_generates_code_for_event(): void
    {
        $expected = <<<php
<?php

declare(strict_types=1);

namespace App\Entities;

use App\Common\Entity;

class Test implements Entity
{
    public \$foo;
    public \$bar;
}

php;

        $event = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'string'),
            ])
        );

        $code = $this->entityGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

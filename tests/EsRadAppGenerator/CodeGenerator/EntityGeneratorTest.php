<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\EntityStuff\Output\Entity;
use EsRadAppGenerator\EntityStuff\Output\Property;
use EsRadAppGenerator\EntityStuff\Output\PropertyCollection;
use PHPUnit\Framework\TestCase;

class EntityGeneratorTest extends TestCase
{
    private EntityGenerator $entityGenerator;
    
    protected function setUp(): void
    {
        $this->entityGenerator = new EntityGenerator();
    }

    function test_it_generates_code_for_event()
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
        $entityGenerator = new EntityGenerator();

        $event = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo'),
                Property::new('bar'),
            ])
        );

        $code = $this->entityGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

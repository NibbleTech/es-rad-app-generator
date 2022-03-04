<?php
declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\Entity;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
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
    public string \$foo;
    public int \$bar;
}

php;

        $event = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'int'),
            ])
        );

        $code = $this->entityGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

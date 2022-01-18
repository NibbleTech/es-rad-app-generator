<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\EntityStuff\Output\Event;
use EsRadAppGenerator\EntityStuff\Output\Property;
use EsRadAppGenerator\EntityStuff\Output\PropertyCollection;
use PHPUnit\Framework\TestCase;

class EventGeneratorTest extends TestCase
{
    private EventGenerator $eventGenerator;
    
    protected function setUp(): void
    {
        $this->eventGenerator = new EventGenerator();
    }

    function test_it_generates_code_for_event()
    {
        $expected = <<<php
<?php

declare(strict_types=1);

namespace App\Events;

use App\Common\Event;

class Test implements Event
{
    public \$foo;
    public \$bar;
}

php;

        $event = Event::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'string'),
            ])
        );

        $code = $this->eventGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

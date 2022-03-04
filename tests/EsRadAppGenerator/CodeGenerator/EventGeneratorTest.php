<?php
declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
use PHPUnit\Framework\TestCase;

class EventGeneratorTest extends TestCase
{
    private EventGenerator $eventGenerator;
    
    protected function setUp(): void
    {
        $this->eventGenerator = new EventGenerator();
    }

    function test_it_generates_code_for_event(): void
    {
        $expected = <<<php
<?php

declare(strict_types=1);

namespace App\Events;

use App\Common\Event;

class Test implements Event
{
    public int \$foo;
    public string \$bar;
}

php;

        $event = Event::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'int'),
                Property::new('bar', 'string'),
            ])
        );

        $code = $this->eventGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

<?php
declare(strict_types=1);

namespace EsRadAppGenerator\Components;

use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    function test_it_can_merge_events_and_dedupe_properties(): void
    {
        $eventA = Event::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'int'),
            ])
        );
        $eventB = Event::new(
            'Test',
            PropertyCollection::with([
                Property::new('baz', 'string'),
            ])
        );

        $eventAExpected = Event::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'int'),
                Property::new('baz', 'string'),
            ])
        );

        $eventA->merge($eventB);
        
        $this->assertEquals($eventAExpected, $eventA);
    }

    function test_it_throws_exception_when_merging_events_with_different_classes(): void
    {
        $entityA = Event::new(
            'Test',
            PropertyCollection::with([
            ])
        );
        $entityB = Event::new(
            'NOTTest',
            PropertyCollection::with([
            ])
        );

        $this->expectException(\InvalidArgumentException::class);

        $entityA->merge($entityB);
    }
}

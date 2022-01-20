<?php
declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    function test_it_can_merge_entities_and_dedupe_properties(): void
    {
        $entityA = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'int'),
            ])
        );
        $entityB = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('baz', 'string'),
            ])
        );

        $entityAExpected = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'int'),
                Property::new('baz', 'string'),
            ])
        );

        $entityA->merge($entityB);
        
        $this->assertEquals($entityAExpected, $entityA);
    }

    function test_it_throws_exception_when_merging_entities_with_different_classes(): void
    {
        $entityA = Entity::new(
            'Test',
            PropertyCollection::with([
            ])
        );
        $entityB = Entity::new(
            'NOTTest',
            PropertyCollection::with([
            ])
        );

        $this->expectException(\InvalidArgumentException::class);

        $entityA->merge($entityB);
    }
}

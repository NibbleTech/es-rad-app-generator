<?php

declare(strict_types=1);

namespace EsRadAppGenerator\EntityStuff\Output;

use EsRadAppGenerator\EntityStuff\Output\SideEffects\Creation;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Update;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class InstructionTest extends TestCase
{
    public function test_it_constructs()
    {
        $event = Event::new(
            'Foo\\Test',
            PropertyCollection::with([
                Property::new('testEventProp1', 'string'),
                Property::new('testEventProp2', 'string'),
            ])
        );

        $sideEffects = [
            Creation::forEntityClass(
                'Foo\\Test',
                [
                    EventEntityPropertyMapping::with(
                        Property::new('testEntityProp1', 'string'),
                        Property::new('testEventProp1', 'string'),
                    )
                ]
            ),
            Update::forEntityClass(
                'Foo\\Test',
                [
                    EventEntityPropertyMapping::with(
                        Property::new('testEntityProp2', 'string'),
                        Property::new('testEventProp2', 'string'),
                    )
                ]
            ),
        ];

        $expectedEntities = [
            Entity::new(
                'Foo\\Test',
                PropertyCollection::with([
                    Property::new('testEntityProp1', 'string'),
                    Property::new('testEntityProp2', 'string'),
                ])
            ),
        ];

        $instruction = Instruction::new(
            'Test thing',
            $event,
            $sideEffects
        );

        assertEquals($instruction->getName(), 'Test thing');
        assertEquals($instruction->getEvent(), $event);
        assertEquals($instruction->getListenerName(), 'TestThing');
        assertEquals($instruction->getSideEffects(), $sideEffects);
        assertEquals($instruction->getEntities(), $expectedEntities);
    }
}

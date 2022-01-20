<?php

declare(strict_types=1);

namespace EsRadAppGenerator\TestHelpers;

use EsRadAppGenerator\EntityStuff\Output\Entity;
use EsRadAppGenerator\EntityStuff\Output\Event;
use EsRadAppGenerator\EntityStuff\Output\EventEntityPropertyMapping;
use EsRadAppGenerator\EntityStuff\Output\Instruction;
use EsRadAppGenerator\EntityStuff\Output\Property;
use EsRadAppGenerator\EntityStuff\Output\PropertyCollection;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Creation;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Deletion;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Update;

/**
 * @TODO want to make this a built in PHPUnit assertion for better
 */
trait ReusableDemoInstructionAssertions
{
    /**
     * @param \EsRadAppGenerator\EntityStuff\Output\Instruction[] $instructions
     *
     * @return void
     */
    protected function assertInstructionsMatchExpectedDemo(array $instructions): void
    {
        $this->assertEqualsCanonicalizing(
            $instructions,
            $this->expectedDemoInstructions()
        );
    }

    /**
     * @return Instruction[]
     */
    private function expectedDemoInstructions(): array
    {
        return [
            Instruction::new(
                'Thing is sent',
                Event::new(
                    'ThingSent',
                    PropertyCollection::with([
                        Property::new('createEventProp1', 'string'),
                    ])
                ),
                [
                    Creation::forEntityClass(
                        'Thing',
                        [
                            EventEntityPropertyMapping::with(
                                Property::new('createEntityProp1', 'string'),
                                Property::new('createEventProp1', 'string'),
                            )
                        ]
                    ),
                ]
            ),
            Instruction::new(
                'Thing is updated',
                Event::new(
                    'ThingUpdated',
                    PropertyCollection::with([
                        Property::new('updateEventProp1', 'string'),
                    ])
                ),
                [
                    Update::forEntityClass(
                        'Thing',
                        [
                            EventEntityPropertyMapping::with(
                                Property::new('updateEntityProp1', 'string'),
                                Property::new('updateEventProp1', 'string'),
                            )
                        ]
                    ),
                ]
            ),
            Instruction::new(
                'Thing is deleted',
                Event::new(
                    'ThingDeleted',
                    PropertyCollection::with([
                        Property::new('deleteEventProp1', 'string'),
                    ])
                ),
                [
                    Deletion::forEntityClass(
                        'Thing',
                        [
                            EventEntityPropertyMapping::with(
                                Property::new('deleteEntityProp1', 'string'),
                                Property::new('deleteEventProp1', 'string'),
                            )
                        ]
                    ),
                ]
            ),
        ];
    }
}
<?php

declare(strict_types=1);

namespace EsRadAppGenerator\TestHelpers;

use EsRadAppGenerator\Components\Entity;
use EsRadAppGenerator\Components\Event;
use EsRadAppGenerator\Components\EventEntityPropertyMapping;
use EsRadAppGenerator\Components\Instruction;
use EsRadAppGenerator\Components\Property;
use EsRadAppGenerator\Components\PropertyCollection;
use EsRadAppGenerator\Components\SideEffects\Creation;
use EsRadAppGenerator\Components\SideEffects\Deletion;
use EsRadAppGenerator\Components\SideEffects\Update;

/**
 * @TODO want to make this a built in PHPUnit assertion for better
 */
trait ReusableDemoInstructionAssertions
{
    /**
     * @param \EsRadAppGenerator\Components\Instruction[] $instructions
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
                        Property::new('createEventProp2', 'int'),
                    ])
                ),
                [
                    Creation::forEntityClass(
                        'Thing',
                        [
                            EventEntityPropertyMapping::with(
                                Property::new('createEntityProp1', 'string'),
                                Property::new('createEventProp1', 'string'),
                            ),
                            EventEntityPropertyMapping::with(
                                Property::new('createEntityProp2', 'int'),
                                Property::new('createEventProp2', 'int'),
                            ),
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
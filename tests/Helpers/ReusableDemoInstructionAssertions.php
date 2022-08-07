<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\TestHelpers;

use NibbleTech\EsRadAppGenerator\Components\Entity;
use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\EventEntityPropertyMapping;
use NibbleTech\EsRadAppGenerator\Components\EventConsumption;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Creation;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Deletion;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Update;
use NibbleTech\EsRadAppGenerator\Domain;

/**
 * @TODO want to make this a built in PHPUnit assertion for better
 */
trait ReusableDemoInstructionAssertions
{
	protected function assertDomainMatchExpectedDemo(Domain $instructions): void
	{
		$expectedDomain = $this->expectedDemoInstructions();
		$this->assertEqualsCanonicalizing(
			$expectedDomain->getEvents(),
			$instructions->getEvents(),
		);
		$this->assertEqualsCanonicalizing(
			$expectedDomain->getEntities(),
			$instructions->getEntities(),
		);
		$this->assertEqualsCanonicalizing(
			$expectedDomain->getEventConsumers(),
			$instructions->getEventConsumers(),
		);
	}

	/**
	 * @return EventConsumption[]
	 */
	private function expectedDemoInstructions(): Domain
	{
		$entities = [
			Entity::new('Thing'),
		];
		$events = [
			Event::new(
				'ThingSent',
				PropertyCollection::with([
					Property::new('createEventProp1', 'string'),
					Property::new('createEventProp2', 'int'),
				])
			),
			Event::new(
				'ThingUpdated',
				PropertyCollection::with([
					Property::new('updateEventProp1', 'string'),
				])
			),
			Event::new(
				'ThingDeleted',
				PropertyCollection::with([
					Property::new('deleteEventProp1', 'string'),
				])
			),
		];
		$eventConsumers = [
			EventConsumption::new(
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
			EventConsumption::new(
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
			EventConsumption::new(
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

		return new Domain(
			$events,
			$entities,
			$eventConsumers
		);
	}
}

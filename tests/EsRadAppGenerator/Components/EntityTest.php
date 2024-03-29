<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components;

use NibbleTech\EsRadAppGenerator\Components\SideEffects\Creation;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
	public function test_it_can_merge_entities_and_dedupe_properties(): void
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

	public function test_it_throws_exception_when_merging_entities_with_different_classes(): void
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

	public function test_it_records_event_to_apply_correctly(): void
	{
		$event = Event::new('Foo');

		$entity = Entity::new('Entity');

		$entity->appliesEvent($event);

		$this->assertEquals(
			["Foo" => $event],
			$entity->getAppliesEvents(),
		);
	}

	public function test_it_adds_entity_properties_correctly_from_side_effect_of_applies_event(): void
	{
		$event = Event::new('Foo');
		$sideEffect = Creation::forEntityClass(
			'Entity',
			[
				EventEntityPropertyMapping::with(
					Property::new('foo', 'string'),
					Property::new('eventFoo', 'string'),
				)
			]
		);

		$entity = Entity::new('Entity');

		$entity->appliesEvent(
			$event,
			[
				$sideEffect
			]
		);

		$properties = $entity->getProperties();

		$this->assertTrue(
			$properties->hasProperty('foo')
		);
	}

	public function test_it_throws_exception_when_trying_to_record_duplicate_event_class_in_applies_events(): void
	{
		$event = Event::new('Foo');
		$sideEffect = Creation::forEntityClass(
			'Entity',
			[
				EventEntityPropertyMapping::with(
					Property::new('foo', 'string'),
					Property::new('eventFoo', 'string'),
				)
			]
		);

		$entity = Entity::new('Entity');

		$entity->appliesEvent(
			$event,
			[
				$sideEffect
			]
		);

		$this->expectException(\InvalidArgumentException::class);

		$entity->appliesEvent(
			$event,
			[
				$sideEffect
			]
		);
	}
}

<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator;

use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
use PHPUnit\Framework\TestCase;

/**
 * @group shaun
 */
class DomainBuilderTest extends TestCase
{
	public function test_it_merges_events(): void
	{
		$domainBuilder = new DomainBuilder();

		$event1 = Event::new(
			'Foo',
			PropertyCollection::with([
				Property::new('prop1', 'string'),
			])
		);

		$event2 = Event::new(
			'Foo',
			PropertyCollection::with([
				Property::new('prop2', 'string'),
			])
		);

		$domainBuilder->addEvent($event1);

		$domainBuilder->addEvent($event2);

		$domain = $domainBuilder->getImmutableDomain();

		$this->assertEqualsCanonicalizing(
			$domain->getEvents(),
			[
				Event::new(
					'Foo',
					PropertyCollection::with([
						Property::new('prop1', 'string'),
						Property::new('prop2', 'string'),
					])
				)
			]
		);
	}

}

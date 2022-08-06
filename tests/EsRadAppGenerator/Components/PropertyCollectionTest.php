<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\Components;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertEqualsCanonicalizing;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class PropertyCollectionTest extends TestCase
{
	private PropertyCollection $collection;

	private array $baseProperties = [];

	public function setUp(): void
	{
		$this->baseProperties = [
			Property::new('foo', 'string'),
			Property::new('bar', 'string'),
			Property::new('baz', 'string'),
		];

		$this->collection = PropertyCollection::with($this->baseProperties);
	}

	public function test_it_constructs(): void
	{
		assertEqualsCanonicalizing($this->baseProperties, $this->collection->getProperties());
	}

	public function test_it_merges_other_collection(): void
	{
		$propertiesToMerge = [
			Property::new('baz', 'string'),
			Property::new('new', 'string'),
		];

		$collectionToMerge = PropertyCollection::with($propertiesToMerge);

		$expectedFinalProperties = [
			Property::new('foo', 'string'),
			Property::new('bar', 'string'),
			Property::new('baz', 'string'),
			Property::new('new', 'string'),
		];

		$this->collection->mergeProperties($collectionToMerge);
		assertEqualsCanonicalizing($expectedFinalProperties, $this->collection->getProperties());
	}

	public function test_it_has_property(): void
	{
		assertTrue($this->collection->hasProperty('foo'));
		assertFalse($this->collection->hasProperty('not there'));
	}

	public function test_it_can_add_new_property(): void
	{
		$newProp = Property::new('new', 'string');

		$this->collection->addProperty(
			$newProp
		);

		assertTrue($this->collection->hasProperty('new'));
		assertEquals($newProp, $this->collection->getProperty('new'));
	}
}

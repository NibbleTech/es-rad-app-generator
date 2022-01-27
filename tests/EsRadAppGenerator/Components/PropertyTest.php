<?php

declare(strict_types=1);

namespace EsRadAppGenerator\Components;

use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

class PropertyTest extends TestCase
{
    public function test_it_can_tell_same_type(): void
    {
        $propertyA = Property::new('a', 'string');
        $propertyB = Property::new('a', 'string');

        assertTrue($propertyA->sameAs($propertyB));
    }

    public function test_it_same_as_false_not_the_same_type(): void
    {
        $propertyA = Property::new('a', 'string');
        $propertyB = Property::new('b', 'int');

        assertFalse($propertyA->sameAs($propertyB));
    }

    public function test_it_same_as_false_not_the_same_name(): void
    {
        $propertyA = Property::new('a', 'string');
        $propertyB = Property::new('b', 'string');

        assertFalse($propertyA->sameAs($propertyB));
    }
}

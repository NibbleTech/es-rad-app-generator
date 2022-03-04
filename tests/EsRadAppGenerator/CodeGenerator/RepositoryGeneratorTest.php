<?php
declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\Entity;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
use PHPUnit\Framework\TestCase;

class RepositoryGeneratorTest extends TestCase
{
    private RepositoryGenerator $repositoryGenerator;
    
    protected function setUp(): void
    {
        $this->repositoryGenerator = new RepositoryGenerator();
    }

    function test_it_generates_code_for_entity(): void
    {
        $expected = <<<php
<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Entities\Test;
use Ramsey\Uuid\UuidInterface;

class TestRepository
{
    public function find(UuidInterface \$id): Test
    {
    }

    public function delete(Test \$entity): void
    {
    }

    public function persist(Test \$entity): Test
    {
    }

    public function findBy(array \$properties): ?Test
    {
    }
}

php;

        $event = Entity::new(
            'Test',
            PropertyCollection::with([
                Property::new('foo', 'string'),
                Property::new('bar', 'string'),
            ])
        );

        $code = $this->repositoryGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

<?php
declare(strict_types=1);

namespace EsRadAppGenerator\CodeGenerator;

use EsRadAppGenerator\EntityStuff\Output\Entity;
use EsRadAppGenerator\EntityStuff\Output\Property;
use EsRadAppGenerator\EntityStuff\Output\PropertyCollection;
use PHPUnit\Framework\TestCase;

class RepositoryGeneratorTest extends TestCase
{
    private RepositoryGenerator $repositoryGenerator;
    
    protected function setUp(): void
    {
        $this->repositoryGenerator = new RepositoryGenerator();
    }

    function test_it_generates_code_for_entity()
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
                Property::new('foo'),
                Property::new('bar'),
            ])
        );

        $code = $this->repositoryGenerator->generate($event);

        $this->assertEquals($expected, $code);

    }
}

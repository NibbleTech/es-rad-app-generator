<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\EventEntityPropertyMapping;
use NibbleTech\EsRadAppGenerator\Components\Instruction;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Creation;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Deletion;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Update;
use PHPUnit\Framework\TestCase;

class ListenerGeneratorTest extends TestCase
{
    private ListenerGenerator $listenerGenerator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->listenerGenerator = new ListenerGenerator(
            new SideEffectCrudCodeGenerator()
        );
    }

    public function test_it_generates_listener_code_for_single_create_side_effect(): void
    {
        $sideEffectA = Creation::forEntityClass(
            'Test',
            [
                EventEntityPropertyMapping::with(
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                )
            ]
        );

        $expected = <<<php
<?php

declare(strict_types=1);

namespace App;

use App\Common\Event;
use App\Common\EventListener;
use App\Entities\Test;
use App\Repositories\TestRepository;

class FooBar implements EventListener
{
    private \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function handle(Event \$event): void
    {
        \$entityTestForCreation = new Test();
        \$entityTestForCreation->foo = \$event->bar;
        \$this->testRepository->persist(\$entityTestForCreation);
    }
}

php;

        $instruction = Instruction::new(
            'foo bar',
            Event::new(
                'Test',
                PropertyCollection::with([
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                ])
            ),
            [
                $sideEffectA
            ]
        );

        $code = $this->listenerGenerator->generate($instruction);

        $this->assertEquals($expected, $code);
    }

    public function test_it_generates_listener_code_for_single_update_side_effect(): void
    {
        $sideEffectA = Update::forEntityClass(
            'Test',
            [
                EventEntityPropertyMapping::with(
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                )
            ]
        );

        $expected = <<<php
<?php

declare(strict_types=1);

namespace App;

use App\Common\Event;
use App\Common\EventListener;
use App\Entities\Test;
use App\Repositories\TestRepository;

class FooBar implements EventListener
{
    private \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function handle(Event \$event): void
    {
        \$entityTestForUpdate = \$this->testRepository->findBy([
            'foo' => \$event->getBar(),
        ]);
        \$entityTestForUpdate->foo = \$event->bar;
        \$this->testRepository->persist(\$entityTestForUpdate);
    }
}

php;

        $instruction = Instruction::new(
            'foo bar',
            Event::new(
                'Test',
                PropertyCollection::with([
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                ])
            ),
            [
                $sideEffectA
            ]
        );

        $code = $this->listenerGenerator->generate($instruction);

        $this->assertEquals($expected, $code);
    }

    public function test_it_generates_listener_code_for_single_delete_side_effect(): void
    {
        $sideEffectA = Deletion::forEntityClass(
            'Test',
            [
                EventEntityPropertyMapping::with(
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                )
            ]
        );

        $expected = <<<php
<?php

declare(strict_types=1);

namespace App;

use App\Common\Event;
use App\Common\EventListener;
use App\Entities\Test;
use App\Repositories\TestRepository;

class FooBar implements EventListener
{
    private \$testRepository;

    public function __construct(TestRepository \$testRepository)
    {
        \$this->testRepository = \$testRepository;
    }

    public function handle(Event \$event): void
    {
        \$entityTestForDeletion = \$this->testRepository->findBy([
            'foo' => \$event->getBar(),
        ]);
        \$this->testRepository->delete(\$entityTestForDeletion);
    }
}

php;

        $instruction = Instruction::new(
            'foo bar',
            Event::new(
                'Test',
                PropertyCollection::with([
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                ])
            ),
            [
                $sideEffectA
            ]
        );

        $code = $this->listenerGenerator->generate($instruction);

        $this->assertEquals($expected, $code);
    }

    public function test_it_generates_listener_code_for_multiple_side_effects(): void
    {
        $sideEffectA = Creation::forEntityClass(
            'CreationEntity',
            [
                EventEntityPropertyMapping::with(
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                )
            ]
        );
        $sideEffectB = Update::forEntityClass(
            'UpdateEntity',
            [
                EventEntityPropertyMapping::with(
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                )
            ]
        );
        $sideEffectC = Deletion::forEntityClass(
            'DeleteEntity',
            [
                EventEntityPropertyMapping::with(
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                )
            ]
        );

        $expected = <<<php
<?php

declare(strict_types=1);

namespace App;

use App\Common\Event;
use App\Common\EventListener;
use App\Entities\CreationEntity;
use App\Entities\DeleteEntity;
use App\Entities\UpdateEntity;
use App\Repositories\CreationEntityRepository;
use App\Repositories\DeleteEntityRepository;
use App\Repositories\UpdateEntityRepository;

class FooBar implements EventListener
{
    private \$creationEntityRepository;
    private \$updateEntityRepository;
    private \$deleteEntityRepository;

    public function __construct(
        CreationEntityRepository \$creationEntityRepository,
        UpdateEntityRepository \$updateEntityRepository,
        DeleteEntityRepository \$deleteEntityRepository
    ) {
        \$this->creationEntityRepository = \$creationEntityRepository;
        \$this->updateEntityRepository = \$updateEntityRepository;
        \$this->deleteEntityRepository = \$deleteEntityRepository;
    }

    public function handle(Event \$event): void
    {
        \$entityCreationEntityForCreation = new CreationEntity();
        \$entityCreationEntityForCreation->foo = \$event->bar;
        \$this->creationEntityRepository->persist(\$entityCreationEntityForCreation);

        \$entityUpdateEntityForUpdate = \$this->updateEntityRepository->findBy([
            'foo' => \$event->getBar(),
        ]);
        \$entityUpdateEntityForUpdate->foo = \$event->bar;
        \$this->updateEntityRepository->persist(\$entityUpdateEntityForUpdate);

        \$entityDeleteEntityForDeletion = \$this->deleteEntityRepository->findBy([
            'foo' => \$event->getBar(),
        ]);
        \$this->deleteEntityRepository->delete(\$entityDeleteEntityForDeletion);
    }
}

php;

        $instruction = Instruction::new(
            'foo bar',
            Event::new(
                'Test',
                PropertyCollection::with([
                    Property::new('foo', 'string'),
                    Property::new('bar', 'string'),
                ])
            ),
            [
                $sideEffectA,
                $sideEffectB,
                $sideEffectC,
            ]
        );

        $code = $this->listenerGenerator->generate($instruction);

        $this->assertEquals($expected, $code);
    }
}

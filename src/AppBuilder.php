<?php

declare(strict_types=1);

namespace EsRadAppGenerator;

use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use EsRadAppGenerator\CodeGenerator\Common\EntityInterfaceGenerator;
use EsRadAppGenerator\CodeGenerator\Common\EventInterfaceGenerator;
use EsRadAppGenerator\CodeGenerator\Common\EventListenerInterfaceGenerator;
use EsRadAppGenerator\CodeGenerator\EntityGenerator;
use EsRadAppGenerator\CodeGenerator\EventGenerator;
use EsRadAppGenerator\CodeGenerator\ListenerGenerator;
use EsRadAppGenerator\CodeGenerator\RepositoryGenerator;
use EsRadAppGenerator\CodeGenerator\SideEffectCrudCodeGenerator;
use EsRadAppGenerator\EntityStuff\Output\Entity;
use EsRadAppGenerator\EntityStuff\Output\Event;
use EsRadAppGenerator\EntityStuff\Output\Instruction;

final class AppBuilder
{
    private string $buildDir;
    private EventGenerator $eventGenerator;
    private EntityGenerator $entityGenerator;
    private RepositoryGenerator $repoGenerator;
    /**
     * @var Instruction[]
     */
    private array $instructions = [];
    /**
     * @var array<string, Event>
     */
    private array $globalEvents = [];
    /**
     * @var array<string, Entity>
     */
    private array $globalEntities = [];

    final public function __construct(
        string $buildDir
    ) {
        $this->buildDir        = $buildDir;
        $this->eventGenerator  = new EventGenerator();
        $this->entityGenerator = new EntityGenerator();
        $this->repoGenerator   = new RepositoryGenerator();
    }

    public function build(): void
    {
        $this->clearBuildDirectory();

        /**
         * @TODO Run through all instructions to generate global list of Events and Entities
         * Need something that can take Event classes and compile all possible properties.
         */

        $this->doCommon();

        $this->doEvents();

        $this->doEntities();

        foreach ($this->instructions as $instruction) {
            $this->doListener($instruction);
        }
    }

    private function clearBuildDirectory(): void
    {
        echo 'Clearing build dir' . PHP_EOL;
        $dir = $this->buildDir;
        $di  = new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS);
        $ri  = new RecursiveIteratorIterator($di, RecursiveIteratorIterator::CHILD_FIRST);
        /** @var SplFileInfo $file */
        foreach ($ri as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }
    }

    private function doCommon(): void
    {
        echo 'Generating Common files ' . PHP_EOL;

        $commonDir = $this->buildDir . '/Common/';

        if (!is_dir($commonDir)) {
            mkdir($commonDir);
        }

        $eventInterfaceGenerator = new EventInterfaceGenerator();

        file_put_contents($commonDir . 'Event.php', $eventInterfaceGenerator->generate());

        $eventListenerInterfaceGenerator = new EventListenerInterfaceGenerator();

        file_put_contents($commonDir . 'EventListener.php', $eventListenerInterfaceGenerator->generate());

        $entityInterfaceGenerator = new EntityInterfaceGenerator();

        file_put_contents($commonDir . 'Entity.php', $entityInterfaceGenerator->generate());
    }

    private function doEvents(): void
    {
        $eventsDir = $this->buildDir . '/Events/';

        if (!is_dir($eventsDir)) {
            mkdir($eventsDir);
        }

        /**
         * Temp until we have the global event/entity compiler.
         */
        foreach ($this->instructions as $instruction) {
            $this->addEventToGlobalList($instruction->getEvent());
        }

        foreach ($this->globalEvents as $globalEvent) {
            $eventCode = $this->eventGenerator->generate($globalEvent);
            $eventPath = $globalEvent->getClass() . '.php';

            echo 'Generating Event ' . $globalEvent->getClass() . PHP_EOL;

            file_put_contents($eventsDir . $eventPath, $eventCode);
        }
    }

    private function doEntities(): void
    {
        $entityDir = $this->buildDir . '/Entities/';
        $repoDir   = $this->buildDir . '/Repositories/';

        if (!is_dir($entityDir)) {
            mkdir($entityDir);
        }
        if (!is_dir($repoDir)) {
            mkdir($repoDir);
        }

        /**
         * Temp until we have the global event/entity compiler.
         */
        foreach ($this->instructions as $instruction) {
            foreach ($instruction->getEntities() as $entity) {
                $this->addEntityToGlobalList($entity);
            }
        }

        foreach ($this->globalEntities as $globalEntity) {
            $entityCode = $this->entityGenerator->generate($globalEntity);
            $entityPath = $globalEntity->getClass() . '.php';

            echo 'Generating Entity ' . $globalEntity->getClass() . PHP_EOL;

            file_put_contents($entityDir . $entityPath, $entityCode);

            echo 'Generating Entity Repository for' . $globalEntity->getClass() . PHP_EOL;

            $repoCode = $this->repoGenerator->generate($globalEntity);
            $repoPath = $globalEntity->getClass() . 'Repository' . '.php';

            file_put_contents($repoDir . $repoPath, $repoCode);
        }
    }

    public function addEventToGlobalList(Event $newEvent): void
    {
        $event = $this->globalEvents[$newEvent->getClass()] ?? $newEvent;

        if ($event !== $newEvent) {
            $event->merge($newEvent);
        }

        $this->globalEvents[$event->getClass()] = $event;
    }

    public function addEntityToGlobalList(Entity $newEntity): void
    {
        $entity = $this->globalEntities[$newEntity->getClass()] ?? $newEntity;

        if ($entity !== $newEntity) {
            $entity->merge($newEntity);
        }

        $this->globalEntities[$entity->getClass()] = $entity;
    }

    public function doListener(Instruction $instruction): void
    {
        $listenerDir = $this->buildDir . '/Listeners/';

        if (!is_dir($listenerDir)) {
            mkdir($listenerDir);
        }

        $listenerGenerator = new ListenerGenerator(
            new SideEffectCrudCodeGenerator()
        );

        echo 'Generating Listener ' . $instruction->getListenerName() . PHP_EOL;

        file_put_contents(
            $listenerDir . $instruction->getListenerName() . '.php',
            $listenerGenerator->generate($instruction)
        );
    }
}

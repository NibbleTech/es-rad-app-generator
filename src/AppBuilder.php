<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator;

use NibbleTech\EsRadAppGenerator\InstructionProviders\InstructionProvider;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use NibbleTech\EsRadAppGenerator\CodeGenerator\Common\EntityInterfaceGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\Common\EventInterfaceGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\Common\EventListenerInterfaceGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\EntityGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\EventGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\ListenerGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\RepositoryGenerator;
use NibbleTech\EsRadAppGenerator\CodeGenerator\SideEffectCrudCodeGenerator;
use NibbleTech\EsRadAppGenerator\Components\Entity;
use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\EventConsumption;

final class AppBuilder
{
	private string $buildDir;
	private InstructionProvider $instructionProvider;
	private EventGenerator $eventGenerator;
	private EntityGenerator $entityGenerator;
	private RepositoryGenerator $repoGenerator;
	/**
	 * @var EventConsumption[]
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
		string $buildDir,
		InstructionProvider $instructionProvider
	) {
		$this->buildDir        = $buildDir;
		$this->instructionProvider = $instructionProvider;
		$this->eventGenerator  = new EventGenerator();
		$this->entityGenerator = new EntityGenerator();
		$this->repoGenerator   = new RepositoryGenerator();
	}

	public function build(): void
	{
		$this->clearBuildDirectory();

		$instructions = $this->instructionProvider->provideInstructions();

		/**
		 * @TODO Need something that can take Event classes and compile all possible properties.
		 */

		$this->generateCommonClasses();

		$this->compileGlobals($instructions);

		$this->generateEvents();

		$this->generateEntities();

		foreach ($this->instructions as $instruction) {
			$this->generateListeners($instruction);
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

	/**
	 * @param EventConsumption[] $instructions
	 *
	 * @return void
	 */
	private function compileGlobals(array $instructions): void
	{
		foreach ($instructions as $instruction) {
			$this->addEventToGlobalList($instruction->getEvent());
			foreach ($instruction->getEntities() as $entity) {
				$this->addEntityToGlobalList($entity);
			}
		}
	}

	private function generateCommonClasses(): void
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

	private function generateEvents(): void
	{
		$eventsDir = $this->buildDir . '/Events/';

		if (!is_dir($eventsDir)) {
			mkdir($eventsDir);
		}

		foreach ($this->globalEvents as $globalEvent) {
			$eventCode = $this->eventGenerator->generate($globalEvent);
			$eventPath = $globalEvent->getClass() . '.php';

			echo 'Generating Event ' . $globalEvent->getClass() . PHP_EOL;

			file_put_contents($eventsDir . $eventPath, $eventCode);
		}
	}

	private function generateEntities(): void
	{
		$entityDir = $this->buildDir . '/Entities/';
		$repoDir   = $this->buildDir . '/Repositories/';

		if (!is_dir($entityDir)) {
			mkdir($entityDir);
		}
		if (!is_dir($repoDir)) {
			mkdir($repoDir);
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

	private function addEventToGlobalList(Event $newEvent): void
	{
		$event = $this->globalEvents[$newEvent->getClass()] ?? $newEvent;

		if ($event !== $newEvent) {
			$event->merge($newEvent);
		}

		$this->globalEvents[$event->getClass()] = $event;
	}

	private function addEntityToGlobalList(Entity $newEntity): void
	{
		$entity = $this->globalEntities[$newEntity->getClass()] ?? $newEntity;

		if ($entity !== $newEntity) {
			$entity->merge($newEntity);
		}

		$this->globalEntities[$entity->getClass()] = $entity;
	}

	private function generateListeners(EventConsumption $instruction): void
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

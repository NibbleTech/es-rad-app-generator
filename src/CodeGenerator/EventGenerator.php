<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\Event;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;

final class EventGenerator
{
	final public function __construct()
	{
	}

	public function generate(Event $event): string
	{
		$file = new PhpFile();
		$file
			->setStrictTypes();

		$namespace = $file->addNamespace('App\Events');

		$namespace->addUse("App\Common\Event");

		$class = $namespace->addClass($event->getClass());

		$class
			->addImplement("App\Common\Event");

		foreach ($event->getProperties()->getProperties() as $property) {
			$class->addProperty(
				$property->getName()
			)->setType(
				$property->getType()
			);
		}

		return (new CustomNettePrinter())->printFile($file);
	}
}

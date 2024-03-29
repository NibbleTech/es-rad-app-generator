<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\CodeGenerator;

use NibbleTech\EsRadAppGenerator\Components\EventConsumption;
use Nette\PhpGenerator\PhpFile;

final class ListenerGenerator
{
	private SideEffectCrudCodeGenerator $sideEffectCrudCodeGenerator;

	final public function __construct(
		SideEffectCrudCodeGenerator $sideEffectCrudCodeGenerator
	) {
		$this->sideEffectCrudCodeGenerator = $sideEffectCrudCodeGenerator;
	}

	public function generate(EventConsumption $instruction): string
	{
		$file = new PhpFile();
		$file
			->setStrictTypes();

		$namespace = $file->addNamespace('App');

		$namespace->addUse("App\Common\EventListener");
		$namespace->addUse("App\Common\Event");

		$class = $namespace->addClass($instruction->getListenerName());

		$class
			->addImplement("App\Common\EventListener");

		$constructorMethod = $class
			->addMethod('__construct');

		$handleMethod = $class
			->addMethod('handle')
			->setReturnType('void');


		$sideEffectCrudGenerationDecorator = SideEffectCrudGenerationDecorator::for(
			$this->sideEffectCrudCodeGenerator,
			$namespace,
			$class,
			$constructorMethod,
			$handleMethod
		);

		foreach ($instruction->getSideEffects() as $sideEffect) {
			$sideEffectCrudGenerationDecorator->decorateWith($sideEffect);
		}

		$handleMethod
			->addParameter('event')
			->setType('App\Common\Event');

		return (new CustomNettePrinter())->printFile($file);
	}
}

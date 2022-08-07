<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\InstructionProviders;

use NibbleTech\EsRadAppGenerator\Components\Entity;
use NibbleTech\EsRadAppGenerator\Configuration\XmlProviders\XmlProvider;
use NibbleTech\EsRadAppGenerator\Components\Event;
use NibbleTech\EsRadAppGenerator\Components\EventEntityPropertyMapping;
use NibbleTech\EsRadAppGenerator\Components\EventConsumption;
use NibbleTech\EsRadAppGenerator\Components\Property;
use NibbleTech\EsRadAppGenerator\Components\PropertyCollection;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Creation;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Deletion;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\SideEffect;
use NibbleTech\EsRadAppGenerator\Components\SideEffects\Update;
use NibbleTech\EsRadAppGenerator\Domain;
use NibbleTech\EsRadAppGenerator\DomainBuilder;
use RuntimeException;
use SimpleXMLElement;

class InstructionProvider
{
	private XmlProvider $xmlProvider;

	public function __construct(
		XmlProvider $xmlProvider
	) {
		$this->xmlProvider = $xmlProvider;
	}

	public function compileDomain(): Domain
	{
		$xml = $this->xmlProvider->provideSimpleXml();

		/**
		 * @TODO validate XML against XSD
		 */

		$domainBuilder = new DomainBuilder();

		/** @var SimpleXMLElement $eventConsumers */
		$eventConsumers = $xml->eventConsumers;

		foreach ($eventConsumers->children() as $when) {
			$this->generateEventConsumptionFromXmlElement($when, $domainBuilder);
		}

		/** @var SimpleXMLElement $events */
		$events = $xml->events;

		foreach ($events->children() as $event) {
			$this->generateEventFromXmlElement($event, $domainBuilder);
		}

		return $domainBuilder->getImmutableDomain();
	}

	private function generateEventConsumptionFromXmlElement(
		SimpleXMLElement $when,
		DomainBuilder $domainBuilder
	): void {
		$eventProperties = PropertyCollection::with([]);

		$sideEffects = [];

		/** @var SimpleXMLElement $sideEffectsXml */
		$sideEffectsXml = $when->sideEffects;
		foreach ($sideEffectsXml->children() as $sideEffectXml) {
			$sideEffects[] = $this->generateSideEffectFromXml(
				$sideEffectXml,
				$eventProperties,
			);
		}

		$eventConsumption = EventConsumption::new(
			(string) $when->attributes()->description,
			Event::new(
				(string) $when->attributes()->eventName,
				$eventProperties,
			),
			$sideEffects
		);

		$domainBuilder->addEventConsumer($eventConsumption);
	}

	private function generateEventFromXmlElement(
		SimpleXMLElement $eventXml,
		DomainBuilder $domainBuilder
	): void {
		$entity = Entity::new(
			(string) $eventXml->attributes()->appliesTo
		);

		$domainBuilder->addEntity($entity);

		$event = Event::new(
			(string) $eventXml->attributes()->name
		);

		$domainBuilder->addEvent($event);
	}

	private function generateSideEffectFromXml(
		SimpleXMLElement $xml,
		PropertyCollection $eventProperties
	): SideEffect {
		switch ($xml->getName()) {
			case 'create':
				$sideEffectClass = Creation::class;
				break;
			case 'update':
				$sideEffectClass = Update::class;
				break;
			case 'delete':
				$sideEffectClass = Deletion::class;
				break;
			default:
				throw new RuntimeException('Unsupported side effect XML Element provided [' . $xml->getName() . ']');
		}


		/** @var EventEntityPropertyMapping[] $propertyMappings */
		$propertyMappings = [];

		/** @var SimpleXMLElement $propertyMappingsXml */
		$propertyMappingsXml = $xml->propertyMappings;
		foreach ($propertyMappingsXml->children() as $propertyMapElement) {
			$entityProperty = Property::new(
				(string) $propertyMapElement->attributes()->entityProperty,
				(string) ($propertyMapElement->attributes()->entityPropertyType ?? 'string')
			);
			$eventProperty  = Property::new(
				(string) $propertyMapElement->attributes()->eventProperty,
				(string) ($propertyMapElement->attributes()->eventPropertyType ?? 'string')
			);

			$eventProperties->addProperty($eventProperty);

			$propertyMappings[] = EventEntityPropertyMapping::with(
				$entityProperty,
				$eventProperty
			);
		}

		$sideEffect = $sideEffectClass::forEntityClass(
			(string) $xml->attributes()->entity,
			$propertyMappings
		);

		return $sideEffect;
	}
}

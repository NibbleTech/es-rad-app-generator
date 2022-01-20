<?php

declare(strict_types=1);

namespace EsRadAppGenerator\InstructionProviders;

use EsRadAppGenerator\Configuration\XmlProviders\NativeXML;
use EsRadAppGenerator\EntityStuff\Output\Event;
use EsRadAppGenerator\EntityStuff\Output\EventEntityPropertyMapping;
use EsRadAppGenerator\EntityStuff\Output\Instruction;
use EsRadAppGenerator\EntityStuff\Output\Property;
use EsRadAppGenerator\EntityStuff\Output\PropertyCollection;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Creation;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Deletion;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\SideEffect;
use EsRadAppGenerator\EntityStuff\Output\SideEffects\Update;
use RuntimeException;
use SimpleXMLElement;

class NativeXMLInstructionProvider implements InstructionProvider
{
    private NativeXML $xmlProvider;

    public function __construct(
        NativeXML $xmlProvider
    ) {
        $this->xmlProvider = $xmlProvider;
    }

    /**
     * @return Instruction[]
     */
    public function provideInstructions(): array
    {
        $xml = $this->xmlProvider->provideSimpleXml();

        /**
         * @TODO validate XML against XSD
         */

        /** @var Instruction[] $instructions */
        $instructions = [];

        /** @var SimpleXMLElement $events */
        $events = $xml->events;

        foreach ($events->children() as $when) {
            $instructions[] = $this->generateInstructionFromXmlElement($when);
        }

        return $instructions;
    }

    private function generateInstructionFromXmlElement(SimpleXMLElement $when): Instruction
    {
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

        $instruction = Instruction::new(
            (string) $when->attributes()->description,
            Event::new(
                (string) $when->attributes()->eventName,
                $eventProperties,
            ),
            $sideEffects
        );

        return $instruction;
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

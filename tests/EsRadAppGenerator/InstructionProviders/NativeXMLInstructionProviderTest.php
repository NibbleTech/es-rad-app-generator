<?php

declare(strict_types=1);

namespace NibbleTech\EsRadAppGenerator\InstructionProviders;

use NibbleTech\EsRadAppGenerator\Configuration\XmlProviders\NativeXML;
use NibbleTech\EsRadAppGenerator\Configuration\XmlProviders\XmlProvider;
use NibbleTech\EsRadAppGenerator\TestHelpers\ReusableDemoInstructionAssertions;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class NativeXMLInstructionProviderTest extends TestCase
{
    use ReusableDemoInstructionAssertions;

    private InstructionProvider $instructionProvider;

    protected function setUp(): void
    {
        $xmlString = file_get_contents(__DIR__ . '/../../demo.xml');
        $anonXml = new class ($xmlString) implements XmlProvider {
            public function __construct(
                private string $xmlString
            ) {
            }
            public function provideSimpleXml(): SimpleXMLElement
            {
                return new SimpleXMLElement($this->xmlString);
            }
        };

        $this->instructionProvider = new InstructionProvider(
            $anonXml
        );
    }

    public function test_it_produces_correct_instructions(): void
    {
        $instructions = $this->instructionProvider->provideInstructions();

        $this->assertInstructionsMatchExpectedDemo($instructions);
    }
}

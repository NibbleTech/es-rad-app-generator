<?php
declare(strict_types=1);

namespace EsRadAppGenerator\InstructionProviders;

use EsRadAppGenerator\Configuration\XmlProviders\NativeXML;
use EsRadAppGenerator\TestHelpers\ReusableDemoInstructionAssertions;
use PHPUnit\Framework\TestCase;

class NativeXMLInstructionProviderTest extends TestCase
{
    use ReusableDemoInstructionAssertions;

    private NativeXMLInstructionProvider $instructionProvider;

    protected function setUp(): void
    {
        $nativeXML = new NativeXML(__DIR__ . '/../../demo.xml');
        $this->instructionProvider = new NativeXMLInstructionProvider(
            $nativeXML
        );
    }

    public function test_it_produces_correct_instructions(): void
    {
        $instructions = $this->instructionProvider->provideInstructions();

        $this->assertInstructionsMatchExpectedDemo($instructions);
    }
}

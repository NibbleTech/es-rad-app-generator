<?php

declare(strict_types=1);

namespace EsRadAppGenerator\Configuration\XmlProviders;

use RuntimeException;
use SimpleXMLElement;

class NativeXML implements XmlProvider
{
    public function __construct(
        private string $pathToXmlFile
    ) {
    }

    public function provideSimpleXml(): SimpleXMLElement
    {
        $simpleXml = simplexml_load_file($this->pathToXmlFile);

        if ($simpleXml === false) {
            throw new RuntimeException("XML file failed to load.");
        }

        return $simpleXml;
    }
}

<?php

declare(strict_types=1);

namespace EsRadAppGenerator\Configuration\XmlProviders;

use SimpleXMLElement;

interface XmlProvider
{
    /**
     * Do we need this step? Generation can just happen when we get it.
     *
     * Do we need the generation as a separate step for potential cache-ability?
     */
    public function provideSimpleXml(): SimpleXMLElement;
}

<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
	->in(__DIR__ . '/tests')
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        '@PSR12' => true,
    ])
    ->setIndent("\t")
    ->setFinder($finder)
;
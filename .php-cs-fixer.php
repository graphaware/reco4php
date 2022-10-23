<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('.github')
    ->exclude('vendor')
;

$config = new PhpCsFixer\Config();

return $config
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
    ])
    ->setUsingCache(false)
    ->setFinder($finder)
;

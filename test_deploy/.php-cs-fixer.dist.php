<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpCsFixer\Finder;
use GinCms\CodeStyle\CmsConfig;

$finder = Finder::create()
    ->in(
        [
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ]
    );

return (new PhpCsFixer\Config())
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules(CmsConfig::getRules());

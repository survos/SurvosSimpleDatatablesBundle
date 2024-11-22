<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictNativeCallRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withRules([
        ReturnTypeFromStrictNativeCallRector::class,
    ])
    // uncomment to reach your current PHP version
    ->withPhpSets(php82: true)
    ->withTypeCoverageLevel(10)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(4);

<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Symfony\Set\SymfonySetList;
use Rector\Symfony\Symfony42\Rector\MethodCall\ContainerGetToConstructorInjectionRector;
use Rector\Symfony\Symfony44\Rector\ClassMethod\ConsoleExecuteReturnIntRector;
use Rector\Symfony\Symfony53\Rector\Class_\CommandDescriptionToPropertyRector;
use Rector\Symfony\Symfony61\Rector\Class_\CommandPropertyToAttributeRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__.'/DependencyInjection',
        __DIR__.'/Form',
        __DIR__.'/Tests',
        __DIR__.'/Twig',
    ]);

    // register a single rule
    $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
    $rectorConfig->rule(ClassPropertyAssignToConstructorPromotionRector::class);
    $rectorConfig->rule(CommandDescriptionToPropertyRector::class);
    $rectorConfig->rule(CommandPropertyToAttributeRector::class);
    $rectorConfig->rule(ConsoleExecuteReturnIntRector::class);
    $rectorConfig->rule(ContainerGetToConstructorInjectionRector::class);

    // define sets of rules
    $rectorConfig->sets([
        // LevelSetList::UP_TO_PHP_80
        LevelSetList::UP_TO_PHP_82,

        PHPUnitSetList::PHPUNIT_90,

        // SetList::CODE_QUALITY,

        SymfonySetList::SYMFONY_64,
        SymfonySetList::SYMFONY_CODE_QUALITY,
        SymfonySetList::SYMFONY_CONSTRUCTOR_INJECTION,
    ]);
};

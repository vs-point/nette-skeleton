<?php

declare(strict_types=1);

use VsPoint\Infrastructure\Rector\Rule\EntityInterfaceRector;
use Rector\Core\Configuration\Option;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Nette\Set\NetteSetList;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Symfony\Set\SymfonySetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(
        Option::PATHS,
        [
            __DIR__ . '/src/FB/Entity',
            // __DIR__ . '/tests',
        ]
    );

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);

    $containerConfigurator->import(DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES);
    $containerConfigurator->import(NetteSetList::ANNOTATIONS_TO_ATTRIBUTES);

    $services = $containerConfigurator->services();
    $services->set(StringableForToStringRector::class);
    $services->set(EntityInterfaceRector::class);
};

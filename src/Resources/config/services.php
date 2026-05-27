<?php

use PaLabs\EnumBundle\Form\EnumType;
use PaLabs\EnumBundle\Translator\EnumTranslator;
use PaLabs\EnumBundle\Twig\EnumExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services
        ->set(EnumTranslator::class)
        ->public()
        ->args([
            service('translator')
        ]);

    $services
        ->set(EnumType::class)
        ->private()
        ->args([
            service(EnumTranslator::class)
        ])
        ->tag('form.type');

    $services
        ->set(EnumExtension::class)
        ->private()
        ->args([
            service(EnumTranslator::class)
        ])
        ->tag('twig.extension');

};
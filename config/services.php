<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $servicesIdPrefix  = 'hubertinio_sylius_key_value_plugin.';

    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();

    $services->load('Hubertinio\\SyliusKeyValuePlugin\\Controller\\', __DIR__ . '/../src/Controller');
};

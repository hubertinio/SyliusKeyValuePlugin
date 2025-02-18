<?php

use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorageInterface;
use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorage;
use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorageCachable;
use Hubertinio\SyliusKeyValuePlugin\Storage\UserKeyValueStorage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $servicesIdPrefix = 'hubertinio_sylius_key_value_plugin.';

    $services = $containerConfigurator->services();
    $services->defaults()->public()->autowire()->autoconfigure();

    $services->load('Hubertinio\\SyliusKeyValuePlugin\\Controller\\', __DIR__ . '/../src/Controller');

    $services
        ->set($servicesIdPrefix . 'repository', \Hubertinio\SyliusKeyValuePlugin\Repository\KeyValueRepository::class)
        ->arg('$registry', service('doctrine.orm.default_entity_manager'));


    $services->alias(
        $servicesIdPrefix . 'storage',
        $servicesIdPrefix . 'storage.cachable_key_value'
    );

    $services
        ->set($servicesIdPrefix . 'storage.database_key_value', KeyValueStorage::class)
        ->arg('$repository', $servicesIdPrefix . '.repository');

    $services
        ->set($servicesIdPrefix . 'storage.cachable_key_value', KeyValueStorageCachable::class)
        ->arg('$keyValueStorage', service($servicesIdPrefix . 'storage.database_key_value'))
        ->arg('$cache', service('cache.app'));

    $services
        ->set($servicesIdPrefix . 'storage.user_key_value', UserKeyValueStorage::class)
        ->arg('$keyValueStorage', service($servicesIdPrefix . 'storage'));
};

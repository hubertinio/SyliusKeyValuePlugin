<?php

use Hubertinio\SyliusKeyValuePlugin\Command\TestCommand;
use Hubertinio\SyliusKeyValuePlugin\Repository\KeyValueRepository;
use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorage;
use Hubertinio\SyliusKeyValuePlugin\Storage\KeyValueStorageCacheable;
use Hubertinio\SyliusKeyValuePlugin\Storage\UserKeyValueStorage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $servicesIdPrefix = 'hubertinio_sylius_key_value_plugin.';

    $services = $containerConfigurator->services();
    $services->defaults()->autowire(false)->autoconfigure(false);

    //$services->load('Hubertinio\\SyliusKeyValuePlugin\\Controller\\', __DIR__ . '/../src/Controller');

    $services
        ->set($servicesIdPrefix . 'repository', KeyValueRepository::class)
        ->arg('$registry', service('doctrine'));

    $services->alias(
        $servicesIdPrefix . 'storage',
        $servicesIdPrefix . 'storage.database_key_value'
    );

    $services->alias(
        $servicesIdPrefix . 'storage.cacheable',
        $servicesIdPrefix . 'storage.cacheable_key_value'
    );

    $services->alias(
        $servicesIdPrefix . 'storage.user',
        $servicesIdPrefix . 'storage.user_key_value'
    );

    $services
        ->set($servicesIdPrefix . 'storage.database_key_value', KeyValueStorage::class)
        ->arg('$repository', service($servicesIdPrefix . 'repository'));

    $services
        ->set($servicesIdPrefix . 'storage.cacheable_key_value', KeyValueStorageCacheable::class)
        ->arg('$keyValueStorage', service($servicesIdPrefix . 'storage.database_key_value'))
        ->arg('$cache', service('cache.app'));

    $services
        ->set($servicesIdPrefix . 'storage.user_key_value', UserKeyValueStorage::class)
        ->arg('$keyValueStorage', service($servicesIdPrefix . 'storage.cacheable'));

    $services
        ->set($servicesIdPrefix . 'command.test', TestCommand::class)
        ->arg('$keyValue', service($servicesIdPrefix . 'storage'))
        ->arg('$keyValueCacheable', service($servicesIdPrefix . 'storage.cacheable'))
        ->arg('$userKeyValue', service($servicesIdPrefix . 'storage.user'))
        ->tag('console.command')
        ->public();
};

<?php

declare(strict_types=1);

namespace Hubertinio\SyliusKeyValuePlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('hubertinio_sylius_key_value_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}

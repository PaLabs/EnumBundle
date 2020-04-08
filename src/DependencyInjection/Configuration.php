<?php


namespace PaLabs\EnumBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('pa_enum');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('translator')
                    ->children()
                        ->scalarNode('domain')
                        ->defaultValue('enums')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('doctrine')
                    ->children()
                        ->arrayNode('path')
                            ->scalarPrototype()->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
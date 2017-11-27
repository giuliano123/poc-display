<?php

namespace AdminBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode    = $treeBuilder->root('etf1_cms_filer');
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('aws3')
                    ->children()
                        ->scalarNode('bucket')->isRequired()->end()
                        ->scalarNode('base_path')->defaultValue('')->end()
                        ->variableNode('configuration')->isRequired()->end()
                    ->end()
                ->end()
            ->end();
        return $treeBuilder;
    }
}
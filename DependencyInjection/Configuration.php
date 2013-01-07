<?php

namespace Avro\SupportBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 */
class Configuration
{
    /**
     * Generates the configuration tree.
     *
     * @return NodeInterface
     */
    public function getConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('avro_support', 'array')
            ->children()
                ->scalarNode('db_driver')->defaultValue('mongodb')->cannotBeEmpty()->end()
                ->scalarNode('email_signature')->cannotBeEmpty()->end()
                ->scalarNode('redirect_route')->cannotBeEmpty()->end()
                ->booleanNode('send_email')->defaultTrue()->end()
                ->scalarNode('from_email')->cannotBeEmpty()->end()
                ->scalarNode('min_role')->defaultValue('ROLE_USER')->end()
                ->arrayNode('question')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->cannotBeEmpty()->end()
                        ->booleanNode('allow_anon')->defaultFalse()->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('avro_support_question')->end()
                                ->scalarNode('name')->defaultValue('avro_support_question')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('answer')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('avro_support_answer')->end()
                                ->scalarNode('name')->defaultValue('avro_support_answer')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('category')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')->defaultValue('Avro\SupportBundle:Category')->cannotBeEmpty()->end()
                        ->arrayNode('form')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('type')->defaultValue('avro_support_category')->end()
                                ->scalarNode('name')->defaultValue('avro_support_category')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder->buildTree();
    }
}
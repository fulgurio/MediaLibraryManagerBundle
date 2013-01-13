<?php

namespace Fulgurio\MediaLibraryManagerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fulgurio_media_library_manager');

        $rootNode
            ->children()
                ->scalarNode('cover_size')->defaultValue(50)->end()
            ->end()
            ->children()
                ->arrayNode('amazon')
                    ->children()
                        ->scalarNode('secret_key')->end()
                        ->scalarNode('access_key_id')->end()
                        ->scalarNode('associate_tag')->end()
                        ->scalarNode('aws_url')->defaultValue('http://webservices.amazon.com/AWSECommerceService/AWSECommerceService.wsdl')->end()
                        ->scalarNode('aws_security_url')->defaultValue('http://security.amazonaws.com/doc/2007-01-01/')->end()
                    ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}

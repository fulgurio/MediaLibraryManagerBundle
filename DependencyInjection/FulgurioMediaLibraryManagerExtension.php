<?php

namespace Fulgurio\MediaLibraryManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FulgurioMediaLibraryManagerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
       	$container->setParameter('fulgurio_media_library_manager.cover_size', $config['cover_size']);
       	if (isset($config['amazon']))
       	{
       	    $container->setParameter('fulgurio_media_library_manager.amazon.aws_url', $config['amazon']['aws_url']);
       	    $container->setParameter('fulgurio_media_library_manager.amazon.aws_security_url', $config['amazon']['aws_security_url']);
       	    if (isset($config['amazon']['secret_key'])
       	        && isset($config['amazon']['access_key_id'])
       	        && isset($config['amazon']['secret_key'])
       	    )
       	    {
       	        $container->setParameter('fulgurio_media_library_manager.amazon.secret_key', $config['amazon']['secret_key']);
       	        $container->setParameter('fulgurio_media_library_manager.amazon.access_key_id', $config['amazon']['access_key_id']);
       	        $container->setParameter('fulgurio_media_library_manager.amazon.associate_tag', $config['amazon']['associate_tag']);
       	    }
       	}
    }
}

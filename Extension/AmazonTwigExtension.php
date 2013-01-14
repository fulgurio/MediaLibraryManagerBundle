<?php
namespace Fulgurio\MediaLibraryManagerBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class AmazonTwigExtension extends \Twig_Extension
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'useAmazon' => new \Twig_Function_Method($this, 'useAmazon'),
            'hasMediaInfo' => new \Twig_Function_Method($this, 'hasMediaInfo')
        );
    }

    /**
     * Check if amazon confi is set, so we active it
     */
    public function useAmazon()
    {
            return ($this->container->hasParameter('fulgurio_media_library_manager.amazon.secret_key')
                    && $this->container->hasParameter('fulgurio_media_library_manager.amazon.access_key_id')
                    && $this->container->hasParameter('fulgurio_media_library_manager.amazon.associate_tag'));
    }

    /**
     * Check if amazon confi is set, so we active it
     */
    public function hasMediaInfo()
    {
        return ($this->container->hasParameter('nass600_media_info.provider.music'));
    }

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'MLM_Amazon_extension';
    }
}
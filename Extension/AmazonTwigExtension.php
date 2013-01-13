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
            'useAmazon' => new \Twig_Function_Method($this, 'useAmazon')//, array('is_safe' => array('html'))),
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
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'MLM_Amazon_extension';
    }
}
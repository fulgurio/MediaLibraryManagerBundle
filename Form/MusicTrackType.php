<?php
namespace Fulgurio\MediaLibraryManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MusicTrackType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//         ->add('volume_number', 'hidden', array('required' => TRUE))
//         ->add('track_number', 'hidden', array('required' => TRUE))
        ->add('title', 'text', array('max_length' => 128))
        ->add('duration', 'number', array('required' => FALSE))
        ->add('lyrics', 'text', array('required' => FALSE))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack',
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'MusicTrack';
    }
}

<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MusicTrackType extends AbstractType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('volume_number', 'hidden', array('required' => true))
//            ->add('track_number', 'hidden', array('required' => true))
            ->add('title', 'text', array(
//                'label'      => 'tracks.fields.title.label',
                'max_length' => 128
            ))
            ->add('duration', 'number', array(
//                'label'    => 'tracks.fields.duration.label',
                'required' => false
            ))
            ->add('lyrics', 'textarea', array(
                'label'    => 'tracks.fields.lyrics.label',
                'required' => false
            ));
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'music',
            'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack'
        ));
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'MusicTrack';
    }
}

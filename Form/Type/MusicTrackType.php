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

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
//            ->add('volume_number', HiddenType::class, array('required' => true))
//            ->add('track_number', HiddenType::class, array('required' => true))
            ->add('title', TextType::class, array(
//                'label'      => 'tracks.fields.title.label',
                'attr' => [
                    'max_length' => 128
                ]
            ))
            ->add('duration', NumberType::class, array(
//                'label'    => 'tracks.fields.duration.label',
                'required' => false
            ))
            ->add('lyrics', TextareaType::class, array(
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
            'data_class' => MusicTrack::class
        ));
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'MusicTrack';
    }
}

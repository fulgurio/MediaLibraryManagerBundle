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

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Fulgurio\MediaLibraryManagerBundle\Form\Type\MusicTrackType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class MusicAlbumType extends AbstractType
{
    /**
     * @inheritdoc
     **/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', TextType::class, array(
                'label'    => 'fields.artist.label',
                'required' => false
            ))
            ->add('title', TextType::class, array(
                'label' => 'fields.title.label'
            ))
            ->add('ean', TextType::class, array(
                'label'      => 'fields.ean.label',
                'required'   => false,
                'max_length' => 13
            ))
            //@todo : add media type into configuration
            ->add('media_type', ChoiceType::class, array(
                    'label'           => 'fields.media_type.label',
                    'choices'         => array(
                        'fields.media_type.types.1' => '1',
                        'fields.media_type.types.2' => '2',
                        'fields.media_type.types.3' => '3'
                    ),
                    'choices_as_values' => true,
                    'required'        => true,
                    'invalid_message' => 'music.media_type.invalid'
                )
            )
            ->add('publication_year', NumberType::class, array(
                'label'           => 'fields.publication_year.label',
                'required'        => false,
                'invalid_message' => 'fields.publication_year.invalid',
                'max_length'      => 4
            ))
            ->add('publisher', TextType::class, array(
                'label'    => 'fields.publisher.label',
                'required' => false
            ))
            ->add('coverFile', VichImageType::class, array(
                'label'           => 'fields.cover.label',
                'required'        => false,
                'invalid_message' => 'fields.cover.invalid',
                'allow_delete'    => true,
                'download_link'   => false,
                'constraints' => array(
                    new File(array(
                        'mimeTypes' => array('image/png', 'image/jpeg', 'image/jpg'),
                        'mimeTypesMessage' => 'fields.cover.not_a_image',
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'fields.cover.max_file_size'
                    ))
                )
            ))
            ->add('cover_url', HiddenType::class, array(
                'required' => false,
                'mapped'   => false
            ))
            ->add('tracks', CollectionType::class, array(
                'label'        => 'tracks.title',
                'type'         => MusicTrackType::class,
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('submit', SubmitType::class, array(
                'label'              => 'save',
                'translation_domain' => 'common'
            ));
    }

    /**
     * @inheritdoc
     **/
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'music',
            'data_class' => MusicAlbum::class
        ));
    }

    /**
     * @inheritdoc
     **/
    public function getBlockPrefix()
    {
        return 'music_album';
    }
}
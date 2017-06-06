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

use Fulgurio\MediaLibraryManagerBundle\Form\Type\MusicTrackType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class MusicAlbumType extends AbstractType
{
    /**
     * @inheritdoc
     **/
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', 'text', array(
                'label'    => 'fields.artist.label',
                'required' => false
            ))
            ->add('title', 'text', array(
                'label' => 'fields.title.label'
            ))
            ->add('ean', 'text', array(
                'label'      => 'fields.ean.label',
                'required'   => false,
                'max_length' => 13
            ))
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                    'label'           => 'fields.media_type.label',
                    'choices'         => array(
                        '1'           => 'fields.media_type.types.1',
                        '2'           => 'fields.media_type.types.2',
                        '3'           => 'fields.media_type.types.3'
                    ),
                    'required'        => true,
                    'invalid_message' => 'music.media_type.invalid'
                )
            )
            ->add('publication_year', 'number', array(
                'label'           => 'fields.publication_year.label',
                'required'        => false,
                'invalid_message' => 'fields.publication_year.invalid',
                'max_length'      => 4
            ))
            ->add('publisher', 'text', array(
                'label'    => 'fields.publisher.label',
                'required' => false
            ))
            ->add('coverFile', 'vich_image', array(
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
            ->add('cover_url', 'hidden', array(
                'required' => false,
                'mapped'   => false
            ))
            ->add('tracks', 'collection', array(
                'label'        => 'tracks.title',
                'type'         => new MusicTrackType(),
                'allow_add'    => true,
                'allow_delete' => true,
                'by_reference' => false
            ))
            ->add('submit', 'submit', array(
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
            'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum'
        ));
    }

    /**
     * @inheritdoc
     **/
    public function getName()
    {
        return 'music_album';
    }
}
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
     * (non-PHPdoc)
     * @see Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', 'text', array(
                'required' => FALSE
            ))
            ->add('title', 'text')
            ->add('ean', 'text', array(
                'required'   => FALSE,
                'max_length' => 13
            ))
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                'choices'  => array('cd', 'mp3', 'vinyl'),
                'required' => TRUE,
                'invalid_message' => 'music.media_type.invalid'
                )
            )
            ->add('publication_year', 'number', array(
                'required' => FALSE,
                'invalid_message' => 'music.publication_year.invalid',
                'max_length' => 4
            ))
            ->add('publisher', 'text', array(
                'required' => FALSE
            ))
            ->add('cover_file', 'file', array(
                'required' => FALSE,
                'invalid_message' => 'music.cover.invalid',
                'constraints' => array(
                    new File(array(
                        'mimeTypes' => array('image/png', 'image/jpeg', 'image/jpg'),
                        'mimeTypesMessage' => 'music.cover.not_a_image',
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'music.cover.max_file_size'
                    ))
                )
            ))
            ->add('cover_url', 'hidden', array(
                'required' => FALSE,
                'mapped'   => FALSE
            ))
            ->add('tracks', 'collection', array(
                    'type'         => new MusicTrackType(),
                    'allow_add'    => TRUE,
                    'allow_delete' => TRUE,
                    'by_reference' => FALSE
            ))
            ->add('submit', 'submit');
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum',
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'music_album';
    }
}
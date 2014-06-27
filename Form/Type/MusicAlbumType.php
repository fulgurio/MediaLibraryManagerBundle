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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MusicAlbumType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', 'text', array('max_length' => 128, 'invalid_message' => 'validator.invalid.artist'))
            ->add('title', 'text', array('max_length' => 128, 'required' => TRUE, 'invalid_message' => 'validator.invalid.title'))
            ->add('ean', 'text', array('max_length' => 13, 'invalid_message' => 'validator.invalid.ean'))
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                'choices'   => array('cd', 'mp3', 'vinyl'),
                'required' => TRUE,
                'invalid_message' => 'validator.invalid.artist'
                )
            )
            ->add('publication_year', 'number', array('invalid_message' => 'validator.invalid.publication_year'))
            ->add('publisher', 'text', array('max_length' => 32, 'invalid_message' => 'validator.invalid.publisher'))
            ->add('cover_file', 'file', array('invalid_message' => 'validator.invalid.cover'))
            ->add('cover_url', 'hidden', array('required' => FALSE))
            ->add('tracks', 'collection', array(
                    'type' => new MusicTrackType(),
                    'allow_add' => TRUE,
                    'allow_delete' => TRUE,
                    'by_reference' => FALSE
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum',
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'music_album';
    }
}
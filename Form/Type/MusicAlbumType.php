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

class MusicAlbumType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', 'text')
            ->add('title', 'text')
            ->add('ean', 'text')
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                'choices'   => array('cd', 'mp3', 'vinyl'),
                'required' => TRUE,
                'invalid_message' => 'validator.invalid.media_type'
                )
            )
            ->add('publication_year', 'number', array('invalid_message' => 'validator.invalid.publication_year'))
            ->add('publisher', 'text')
            ->add('cover_file', 'file', array('invalid_message' => 'validator.invalid.cover'))
            ->add('cover_url', 'hidden', array('required' => FALSE))
            ->add('tracks', 'collection', array(
                    'type' => new MusicTrackType(),
                    'allow_add' => TRUE,
                    'allow_delete' => TRUE,
                    'by_reference' => FALSE
            ));
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
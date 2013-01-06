<?php
namespace Fulgurio\MediaLibraryManagerBundle\Form;

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

class MusicAlbumType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form.AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('artist', 'text', array('max_length' => 128, 'invalid_message' => 'fulgurio.medialibrarymanager.music.invalid_artist'))
            ->add('title', 'text', array('max_length' => 128, 'required' => TRUE, 'invalid_message' => 'fulgurio.medialibrarymanager.music.invalid_title'))
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                'choices'   => array('cd', 'mp3'),
                'required' => TRUE,
                'invalid_message' => 'fulgurio.medialibrarymanager.music.invalid_artist'
                )
            )
            ->add('publication_year', 'number', array('invalid_message' => 'fulgurio.medialibrarymanager.music.invalid_publication_year'))
            ->add('publisher', 'text', array('max_length' => 32, 'invalid_message' => 'fulgurio.medialibrarymanager.music.invalid_publisher'))
            ->add('file', 'file', array('invalid_message' => 'fulgurio.medialibrarymanager.music.invalid_cover'))
            ->addValidator(new CallbackValidator(function(FormInterface $form) {
                $yearField = $form->get('publication_year');
                if (strval(intval($yearField->getData())) == $yearField->getData() && ($yearField->getData() > date('Y') + 1 || $yearField->getData() < 1900))
                {
                    $yearField->addError(new FormError('fulgurio.medialibrarymanager.music.invalid_publication_year'));
                }
            }));
        ;
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
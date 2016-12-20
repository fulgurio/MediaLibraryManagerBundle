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
use Symfony\Component\Validator\Constraints\File;

class BookType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', 'text', array(
                'label' => 'author.label'
            ))
            ->add('title', 'text', array(
                'label' => 'title.label'
            ))
            ->add('ean', 'text', array(
                'label'      => 'ean.label',
                'required'   => false,
                'max_length' => 13
            ))
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                'label'           => 'media_type.label',
                'choices'         => array(
                    '1'           => 'media_type.types.1',
                    '2'           => 'media_type.types.2'
                ),
                'required'        => true,
                'invalid_message' => 'validator.invalid.media_type'
                )
            )
            ->add('publication_year', 'number', array(
                'label'           => 'publication_year.label',
                'invalid_message' => 'book.publication_year.invalid',
                'max_length'      => 4
            ))
            ->add('publisher', 'text', array(
                'label'    => 'publisher.label',
                'required' => false
            ))
            ->add('coverFile', 'vich_image', array(
                'label'           => 'cover.label',
                'invalid_message' => 'validator.invalid.cover',
                'allow_delete'    => true,
                'download_link'   => false,
                'required'        => false,
                'constraints' => array(
                    new File(array(
                        'mimeTypes' => array('image/png', 'image/jpeg', 'image/jpg'),
                        'mimeTypesMessage' => 'music.cover.not_a_image',
                        'maxSize' => '2M',
                        'maxSizeMessage' => 'music.cover.max_file_size'
                    ))
                )
            ))
            ->add('submit', 'submit', array(
                'label'              => 'save',
                'translation_domain' => 'common'
            ));
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'book',
            'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\Book'
        ));
    }

    /**
     * (non-PHPdoc)
     * @see \Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'book';
    }
}
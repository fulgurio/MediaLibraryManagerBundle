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

class BookType extends AbstractType
{
    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form\AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', 'text')
            ->add('title', 'text')
            ->add('ean', 'text')
            //@todo : add media type into configuration
            ->add('media_type', 'choice', array(
                'choices'   => array('book', 'ebook'),
                'required' => TRUE,
                'invalid_message' => 'validator.invalid.media_type'
                )
            )
            ->add('publication_year', 'number', array('invalid_message' => 'validator.invalid.publication_year'))
            ->add('publisher', 'text')
            ->add('cover_file', 'file', array('invalid_message' => 'validator.invalid.cover'));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form\AbstractType::configureOptions()
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'Fulgurio\MediaLibraryManagerBundle\Entity\Book',
        ));
    }

    /**
     * (non-PHPdoc)
     * @see Symfony\Component\Form\FormTypeInterface::getName()
     */
    public function getName()
    {
        return 'book';
    }
}
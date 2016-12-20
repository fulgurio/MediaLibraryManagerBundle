<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Form\Handler;

use Fulgurio\MediaLibraryManagerBundle\Entity\Book;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class BookHandler
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * BookHandler constructor.
     *
     * @param RegistryInterface $doctrine
     * @param Form $form
     * @param Request $request
     */
    public function __construct(RegistryInterface $doctrine, Form $form, Request $request)
    {
        $this->doctrine = $doctrine;
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * Processing form values
     *
     * @param Book $book
     * @return boolean
     */
    public function process(Book $book)
    {
        if ($this->request->getMethod() == 'POST')
        {
            $this->form->handleRequest($this->request);
            if ($this->form->isValid())
            {
                $em = $this->doctrine->getEntityManager();
                $em->persist($book);
                $em->flush();
                return true;
            }
            return false;
        }
    }
}
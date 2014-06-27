<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Controller;

use Fulgurio\MediaLibraryManagerBundle\Entity\Book;
use Fulgurio\MediaLibraryManagerBundle\Form\Type\BookType;
use Fulgurio\MediaLibraryManagerBundle\Form\Handler\BookHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BookController extends Controller
{
    /**
     * Books listing
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        // @todo : Filter by owner
        $paginator = $this->get('knp_paginator');
        $books = $em->getRepository('FulgurioMediaLibraryManagerBundle:Book')->findAllWithPaginator($paginator, $this->getRequest()->get('page', 1), $this->getRequest()->get('q'));
        return $this->render(
            'FulgurioMediaLibraryManagerBundle:Book:list.html.twig',
            array(
                'books' => $books
            )
        );
    }

    /**
     * Add new book
     *
     * @param number $bookId
     */
    public function addAction($bookId = NULL)
    {
        $book = is_null($bookId) ? new Book() : $this->getBook($bookId);
        $form = $this->createForm(new BookType(), $book);
        $formHandler = new BookHandler($this->getDoctrine(), $form, $this->getRequest());
        if ($formHandler->process($book))
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans((is_null($bookId) ? 'adding' : 'editing') . '_success', array(), 'book')
            );
            return $this->redirect($this->generateUrl('FulgurioMLM_Book_List'));
        }
        return $this->render(
            'FulgurioMediaLibraryManagerBundle:Book:add.html.twig',
            array(
                'form' => $form->createView(),
                'book' => $book
            )
        );
    }

    /**
     * Remove book
     *
     * @param number $bookId
     */
    public function removeAction($bookId)
    {
        $book = $this->getBook($bookId);
        $request = $this->getRequest();
        if ($request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($book);
            $em->flush();
            return $this->redirect($this->generateUrl('FulgurioMLM_Book_List'));
        }
        else if ($request->get('confirm') === 'no')
        {
            return $this->redirect($this->generateUrl('FulgurioMLM_Book_List'));
        }
        return $this->render('FulgurioMediaLibraryManagerBundle::confirm.html.twig',
            array(
                'action' => $this->generateUrl('FulgurioMLM_Book_Remove', array('bookId' => $bookId)),
                'confirmationMessage' => $this->get('translator')->trans('delete_confirm_message', array('%TITLE%' => $book->getTitle()), 'book'),
        ));
    }

    /**
     * Get book
     *
     * @param number $bookId
     * @return Book
     */
    private function getBook($bookId)
    {
        $em = $this->getDoctrine()->getManager();
        $book = $em->getRepository('FulgurioMediaLibraryManagerBundle:Book')->find($bookId);
        if (is_null($book))
        {
            throw $this->createNotFoundException();
        }
        return $book;
    }
}
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
use Symfony\Component\HttpFoundation\Request;

class BookController extends Controller
{
    /**
     * Books listing
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $books = $em->getRepository('FulgurioMediaLibraryManagerBundle:Book')
                ->findAllWithPaginator(
                        $paginator,
                        $request->get('page', 1),
                        $request->get('q')
        );
        return $this->render(
            'FulgurioMediaLibraryManagerBundle:Book:list.html.twig',
            array('books' => $books)
        );
    }

    /**
     * Add new book
     *
     * @param number $bookId
     * @param Request $request
     * @return Response
     */
    public function addAction($bookId = NULL, Request $request)
    {
        if (is_null($bookId))
        {
            $book = new Book();
            $action = $this->generateUrl('FulgurioMLM_Book_Add');
        }
        else
        {
            $book = $this->getBook($bookId);
            $action = $this->generateUrl('FulgurioMLM_Book_Edit', array('bookId' => $bookId));
        }
        $form = $this->createForm(new BookType(), $book, array('action' => $action));
        $formHandler = new BookHandler($this->getDoctrine(), $form, $request);
        if ($formHandler->process($book))
        {
            $this->addFlash('notice', (is_null($bookId) ? 'adding' : 'editing') . '_success');
            return $this->redirectToRoute('FulgurioMLM_Book_List');
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
     * @param Request $request
     * @return $response
     */
    public function removeAction($bookId, Request $request)
    {
        $book = $this->getBook($bookId);
        if ($request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($book);
            $em->flush();
            return $this->redirectToRoute('FulgurioMLM_Book_List');
        }
        else if ($request->get('confirm') === 'no')
        {
            return $this->redirectToRoute('FulgurioMLM_Book_List');
        }
        return $this->render('FulgurioMediaLibraryManagerBundle::confirm.html.twig',
            array(
                'title' => $this->get('translator')->trans('remove_confirm_title', array(), 'common'),
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
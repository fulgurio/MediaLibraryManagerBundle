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

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Fulgurio\MediaLibraryManagerBundle\Form\Type\MusicAlbumType;
use Fulgurio\MediaLibraryManagerBundle\Form\Handler\MusicAlbumHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MusicController extends Controller
{
    /**
     * Music albums listing
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        $paginator = $this->get('knp_paginator');
        $albums = $em->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')->findAllWithPaginator($paginator, $this->getRequest()->get('page', 1), $this->getRequest()->get('q'));
        return $this->render(
            'FulgurioMediaLibraryManagerBundle:Music:list.html.twig',
            array(
                'albums' => $albums
            )
        );
    }

    /**
     * Add new album
     *
     * @param number $albumId
     */
    public function addAction($albumId = NULL)
    {
        $album = is_null($albumId) ? new MusicAlbum() : $this->getAlbum($albumId);
        $form = $this->createForm(new MusicAlbumType(), $album);
        $formHandler = new MusicAlbumHandler($this->getDoctrine(), $form, $this->getRequest());
        if ($formHandler->process($album))
        {
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans((is_null($albumId) ? 'adding' : 'editing') . '_music_success', array(), 'music')
            );
            return $this->redirect($this->generateUrl('FulgurioMLM_Music_List'));
        }
        return $this->render(
            'FulgurioMediaLibraryManagerBundle:Music:add.html.twig',
            array(
                'form' => $form->createView(),
                'album' => $album
            )
        );
    }

    /**
     * Remove album
     *
     * @param number $albumId
     */
    public function removeAction($albumId)
    {
        $album = $this->getAlbum($albumId);
        $request = $this->getRequest();
        if ($request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($album);
            $em->flush();
            return $this->redirect($this->generateUrl('FulgurioMLM_Music_List'));
        }
        else if ($request->get('confirm') === 'no')
        {
            return $this->redirect($this->generateUrl('FulgurioMLM_Music_List'));
        }
        return $this->render('FulgurioMediaLibraryManagerBundle::confirm.html.twig',
            array(
                'action' => $this->generateUrl('FulgurioMLM_Music_Remove', array('albumId' => $albumId)),
                'confirmationMessage' => $this->get('translator')->trans('delete_confirm_message', array('%TITLE%' => $album->getTitle()), 'music'),
        ));
    }

    /**
     * Retrieve album info from external webservice
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveAlbumInfosAction()
    {
        if (!$this->has('nass600_media_info.music_info.manager'))
        {
            throw new AccessDeniedException();
        }
        $request = $this->getRequest();
        $musicManager = $this->get('nass600_media_info.music_info.manager');
        $data = $musicManager->getAlbumInfo(
            array(
//                 'mbid' => '61bf0388-b8a9-48f4-81d1-7eb02706dfb0',
                'artist' => $request->get('artist'),
                'album' => $request->get('title'),
                'ASIN' => $request->get('asin'),
                'EAN' => $request->get('ean')
            )
        );
//         $lyrics = $this->get('nass600_media_info.lyrics_info.manager');
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Get track lyrics from external webservice
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveLyricsTrackAction()
    {
//        $request = $this->getRequest();
//        $artist = $request->get('artist');
        $data = '';
        return new Response($data);
    }

    /**
     * Get album
     *
     * @param number $albumId
     * @return Album
     */
    private function getAlbum($albumId)
    {
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')->find($albumId);
        if (is_null($album))
        {
            throw $this->createNotFoundException();
        }
        return $album;
    }
}

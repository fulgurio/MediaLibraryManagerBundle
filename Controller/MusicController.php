<?php

namespace Fulgurio\MediaLibraryManagerBundle\Controller;

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack;
use Fulgurio\MediaLibraryManagerBundle\Form\MusicAlbumType;
use Fulgurio\MediaLibraryManagerBundle\Form\MusicAlbumHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MusicController extends Controller
{
    /**
     * Music album listing
     *
     */
    public function listAction()
    {
        $em = $this->getDoctrine()->getManager();
        // @todo : Filter by owner
        $paginator = $this->get('knp_paginator');

        $albums = $em->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')->findAllWithPaginator($paginator, $this->get('request')->get('page', 1), $this->get('request')->get('q'));
        return $this->render(
            'FulgurioMediaLibraryManagerBundle:Music:list.html.twig',
            array(
                'albums' => $albums
            )
        );
    }

    /**
     * Add new album
     * @param integer $albumId
     */
    public function addAction($albumId = NULL)
    {
        $album = is_null($albumId) ? new MusicAlbum() : $this->getAlbum($albumId);
        $album->setCoverSize($this->container->getParameter('fulgurio_media_library_manager.cover_size'));
        $form = $this->createForm(new MusicAlbumType(), $album);
        $formHandler = new MusicAlbumHandler($this->getDoctrine(), $form, $this->get('request'));
        if ($formHandler->process($album))
        {
            $this->get('session')->setFlash(
                'notice',
                $this->get('translator')->trans('fulgurio.medialibrarymanager.music.' . (is_null($albumId) ? 'adding' : 'editing') . '_music_success')
            );
            return new RedirectResponse($this->generateUrl('FulgurioMLM_Music_List'));
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
     * @param integer $albumId
     * @todo : check owner
     */
    public function removeAction($albumId)
    {
        $album = $this->getAlbum($albumId);
        $request = $this->container->get('request');
        if ($request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($album);
            $em->flush();
            return new RedirectResponse($this->generateUrl('FulgurioMLM_Music_List'));
        }
        else if ($request->get('confirm') === 'no')
        {
            // @todo : if pagination; it s better to come back a the same page
            return new RedirectResponse($this->generateUrl('FulgurioMLM_Music_List'));
        }
        $templateName = $request->isXmlHttpRequest() ? 'FulgurioMediaLibraryManagerBundle::confirmAjax.html.twig' : 'FulgurioMediaLibraryManagerBundle::confirm.html.twig';
        return $this->render($templateName, array(
                'action' => $this->generateUrl('FulgurioMLM_Music_Remove', array('albumId' => $albumId)),
                'confirmationMessage' => $this->get('translator')->trans('fulgurio.medialibrarymanager.music.delete_confirm_message', array('%TITLE%' => $album->getTitle())),
        ));
        return $this->render('FulgurioMediaLibraryManagerBundle:Music:list.html.twig');
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
        $request = $this->container->get('request');
        $musicManager  = $this->get('nass600_media_info.music_info.manager');
        $data = $musicManager->getAlbumInfo(
            array(
//                 'mbid' => '61bf0388-b8a9-48f4-81d1-7eb02706dfb0',
                'artist' => $request->get('artist'),
                'album' => $request->get('title')
            )
        );
//         $lyrics = $this->get('nass600_media_info.lyrics_info.manager');
        $response = new Response(json_encode($data));
        $response->headers->set('Content-Type', 'application/json');
        return ($response);
    }

    /**
     * Get album
     * @param integer $albumId
     * @return Album
     */
    private function getAlbum($albumId)
    {
        //@todo : check owner
//         if ($this->get('security.context')->getToken()->getUser() != $album->getOwner())
//         {
//             throw new AccessDeniedException();
//         }
        $em = $this->getDoctrine()->getManager();
        $album = $em->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')->find($albumId);
        if (is_null($album))
        {
            throw $this->createNotFoundException();
        }
        return ($album);
    }
}

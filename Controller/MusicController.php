<?php

namespace Fulgurio\MediaLibraryManagerBundle\Controller;

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Fulgurio\MediaLibraryManagerBundle\Form\MusicAlbumType;
use Fulgurio\MediaLibraryManagerBundle\Form\MusicAlbumHandler;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
        $albums = $em->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')->findAll();
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
        if (is_null($albumId))
        {
            $album = new MusicAlbum();
        }
        else
        {
            $album = $this->getAlbum($albumId);
        }
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
        if ($request->request->get('confirm') === 'yes')
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->remove($album);
            $em->flush();
            return new RedirectResponse($this->generateUrl('FulgurioMLM_Music_List'));
        }
        else if ($request->request->get('confirm') === 'no')
        {
            // @todo : if pagination; it s better to come back a the same page
            return new RedirectResponse($this->generateUrl('FulgurioMLM_Music_List'));
        }
        $templateName = $request->isXmlHttpRequest() ? 'FulgurioMediaLibraryManagerBundle::confirmAjax.html.twig' : 'FulgurioMediaLibraryManagerBundle::confirm.html.twig';
        return $this->render($templateName, array(
                'action' => $this->generateUrl('FulgurioMLM_Music_Remove', array('albumId' => $albumId)),
                'confirmationMessage' => $this->get('translator')->trans('fulgurio.medialibrarymanager.music.delete_confirm_message', array('%ALBUM_NAME%' => $album->getTitle())),
        ));
        return $this->render('FulgurioMediaLibraryManagerBundle:Music:list.html.twig');
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

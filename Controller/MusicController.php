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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MusicController extends Controller
{
    /**
     * Music albums listing
     *
     * @param Request $request
     * @return Response
     */
    public function listAction(Request $request)
    {
        $albums = $this->getDoctrine()
            ->getManager()
            ->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')
            ->findAllWithPaginator(
                $this->get('knp_paginator'),
                $request->get('page', 1),
                $request->get('q')
            );

        return $this->render('FulgurioMediaLibraryManagerBundle:Music:list.html.twig', array(
            'albums' => $albums
            )
        );
    }

    /**
     * Add new album
     *
     * @param number $albumId
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function addAction($albumId = null, Request $request)
    {
        if (is_null($albumId))
        {
            $album = new MusicAlbum();
            $action = $this->generateUrl('FulgurioMLM_Music_Add');
        }
        else
        {
            $album = $this->getAlbum($albumId);
            $action = $this->generateUrl('FulgurioMLM_Music_Edit', array('albumId' => $albumId));
        }
        $form = $this->createForm(MusicAlbumType::class, $album, array('action' => $action));
        $uploadMapping = $this->getParameter('vich_uploader.mappings');
        $formHandler = new MusicAlbumHandler($this->getDoctrine(), $form, $request, $uploadMapping['music_cover']['upload_destination']);
        if ($formHandler->process($album))
        {
            $this->addFlash('notice', (is_null($albumId) ? 'add' : 'edit') . '.success');

            return $this->redirectToRoute('FulgurioMLM_Music_List');
        }

        return $this->render('FulgurioMediaLibraryManagerBundle:Music:add.html.twig', array(
            'form'  => $form->createView(),
            'album' => $album,
            'hasMusicService' => true === $this->container->hasParameter('fulgurio_media_library_manager.music_service')
            )
        );
    }

    /**
     * Remove album
     *
     * @param number $albumId
     * @param Request $request
     * @return Response|RedirectResponse
     */
    public function removeAction($albumId, Request $request)
    {
        $album = $this->getAlbum($albumId);
        if ($request->get('confirm')) {
            if ('yes' === $request->get('confirm'))
            {
                    $em = $this->getDoctrine()->getManager();
                    $em->remove($album);
                    $em->flush();
                    $this->addFlash('notice', 'delete.success');
            }

            return $this->redirectToRoute('FulgurioMLM_Music_List');
        }

        return $this->render('FulgurioMediaLibraryManagerBundle::confirm.html.twig', array(
            'title' => $this->get('translator')->trans('remove_confirmation', array(), 'common'),
            'action' => $this->generateUrl('FulgurioMLM_Music_Remove', array('albumId' => $albumId)),
            'confirmationMessage' => $this->get('translator')->trans('delete.confirm_message', array('%TITLE%' => $album->getTitle()), 'music')
            )
        );
    }

    /**
     * Retrieve album info from external webservice
     *
     * @param Request $request
     * @return JsonResponse
     *
     * @throws \Exception|AccessDeniedHttpException
     */
    public function retrieveAlbumInfosAction(Request $request)
    {
        if (false === $request->isXmlHttpRequest() ||
            false === $this->container->hasParameter('fulgurio_media_library_manager.music_service'))
        {
            throw new AccessDeniedHttpException();
        }
        $serviceName = $this->container->getParameter('fulgurio_media_library_manager.music_service');
        if (false === $this->has($serviceName))
        {
            throw new \Exception('Service ' . $serviceName . ' doesn\'t exist');
        }
        $musicManager = $this->get($serviceName);
        $data = array(
            'artist' => trim($request->get('artist')),
            'album' => trim($request->get('title')),
            'EAN' => trim($request->get('ean'))
        );

        return new JsonResponse($musicManager->getAlbumInfo($data));
    }

    /**
     * Get album
     *
     * @param number $albumId
     * @return MusicAlbum
     *
     * @throws NotFoundHttpException
     */
    private function getAlbum($albumId)
    {
        $album = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('FulgurioMediaLibraryManagerBundle:MusicAlbum')
                        ->find($albumId);
        if (is_null($album))
        {
            throw $this->createNotFoundException();
        }

        return $album;
    }
}

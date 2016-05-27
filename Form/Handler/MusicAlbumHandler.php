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

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class MusicAlbumHandler
{
    private $doctrine;
    private $form;
    private $request;

    public function __construct(RegistryInterface $doctrine, Form $form, Request $request)
    {
        $this->doctrine = $doctrine;
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * Processing form values
     *
     * @param MusicAlbum $album
     * @return boolean
     */
    public function process(MusicAlbum $album)
    {
        if ($this->request->getMethod() == 'POST')
        {
            $originalTracks = array();
            foreach ($album->getTracks() as $track)
            {
                $originalTracks[] = $track;
            }
            $this->form->handleRequest($this->request);
            if ($this->form->isValid()
                    && $this->form->get('submit')->isClicked())
            {
                $em = $this->doctrine->getEntityManager();
                foreach ($album->getTracks() as $trackNb => $track)
                {
                    foreach ($originalTracks as $key => $originalTrack)
                    {
                        if ($originalTrack->getId() == $track->getId())
                        {
                            unset($originalTracks[$key]);
                        }
                    }
                    if (trim($track->getTitle()) == '')
                    {
                        $album->removeTrack($track);
                    }
                    else
                    {
                        $track->setVolumeNumber(1);//@todo
                        $track->setTrackNumber($trackNb + 1);
                        $track->setMusicAlbum($album);
                        $em->persist($track);
                    }
                }
                foreach ($originalTracks as $originalTrack)
                {
                    $album->removeTrack($originalTrack);
                    $em->remove($originalTrack);
                }
//                 $album->setOwner($this->currentUser); //@todo
                $em->persist($album);
                $em->flush();
                return TRUE;
            }
            return FALSE;
        }
    }
}
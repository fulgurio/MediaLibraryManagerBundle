<?php
namespace Fulgurio\MediaLibraryManagerBundle\Form;

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
            if ($this->request->get('realSubmit') !== '1')
            {
                return FALSE;
            }
            if ($this->form->isValid())
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
                return (TRUE);
            }
            return (FALSE);
        }
    }
}
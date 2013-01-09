<?php
namespace Fulgurio\MediaLibraryManagerBundle\Form;

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class MusicAlbumHandler
{
    private $doctrine, $form, $request;

    function __construct($doctrine, $form, $request)
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
            $this->form->bindRequest($this->request);
            if ($this->request->get('realSubmit') !== '1')
            {
                return FALSE;
            }
            if ($this->form->isValid())
            {
                $em = $this->doctrine->getEntityManager();
                if ($album->getId() == 0)
                {
                }
                else
                {
                }
                foreach ($album->getTracks() as $trackNb => $track)
                {
                    $track->setVolumeNumber(1);//@todo
                    $track->setTrackNumber($trackNb + 1);
                    $track->setMusicAlbum($album);
                    $em->persist($track);
                }
//                 $album->setOwner($this->currentUser);
                $em->persist($album);
                $em->flush();
                return (TRUE);
            }
            return (FALSE);
        }
    }
}
<?php
namespace Fulgurio\MediaLibraryManagerBundle\Form;

use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
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
//             if ($this->request->get('realSubmit') !== '1')
//             {
//                 return FALSE;
//             }
            $this->form->bindRequest($this->request);
            if ($this->form->isValid())
            {
                $em = $this->doctrine->getEntityManager();
                if ($album->getId() == 0)
                {
                }
                else
                {
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
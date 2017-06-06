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

use Doctrine\Common\Persistence\ObjectManager;
use Fulgurio\MediaLibraryManagerBundle\Entity\MusicAlbum;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class MusicAlbumHandler
{
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    /**
     * @var Form
     */
    private $form;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var string
     */
    private $uploadPath;


    /**
     * MusicAlbumHandler constructor.
     *
     * @param RegistryInterface $doctrine
     * @param Form $form
     * @param Request $request
     * @param string $uploadPath
     */
    public function __construct(RegistryInterface $doctrine, Form $form, Request $request, $uploadPath)
    {
        $this->doctrine = $doctrine;
        $this->form = $form;
        $this->request = $request;
        $this->uploadPath = $uploadPath;
    }

    /**
     * Processing form values
     *
     * @param MusicAlbum $album
     * @return boolean
     */
    public function process(MusicAlbum $album)
    {
        if ($this->request->isMethod('POST'))
        {
            $originalTracks = array();
            foreach ($album->getTracks() as $track)
            {
                $originalTracks[] = $track;
            }
            $this->form->handleRequest($this->request);
            if ($this->form->isValid() && $this->form->isSubmitted())
            {
                $em = $this->doctrine->getManager();
                $this->initCover($album);
                $this->initTracks($album, $originalTracks, $em);
//                 $album->setOwner($this->currentUser); //@todo
                $em->persist($album);
                $em->flush();

                return true;
            }
        }
        return false;
    }

    /**
     * Init cover
     *
     * @param MusicAlbum $album
     */
    private function initCover(MusicAlbum $album)
    {
        $coverUrl = $this->form->get('cover_url')->getData();
        if ($coverUrl)
        {
            $filename = basename($coverUrl);
            $newFilename = uniqid() . substr($filename, strrpos($filename, '.'));
            $targetName = $this->uploadPath . '/' . $newFilename;
            copy($coverUrl, $targetName);
            $album->setCover($newFilename);
        }
    }

    /**
     * Init tracks
     *
     * @param MusicAlbum $album
     * @param array $originalTracks
     * @param ObjectManager $em
     */
    private function initTracks(MusicAlbum $album, array $originalTracks, ObjectManager $em)
    {
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
    }
}
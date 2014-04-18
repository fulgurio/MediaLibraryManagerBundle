<?php
/*
 * This file is part of the MediaLibraryManagerBundle package.
 *
 * (c) Fulgurio <http://fulgurio.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Fulgurio\MediaLibraryManagerBundle\Entity;

use Fulgurio\MediaLibraryManagerBundle\Entity\MediaCoverAbstract;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;

/**
 * MusicAlbum
 */
class MusicAlbum extends MediaCoverAbstract
{
    /**
     * @var integer
     *
     */
    private $id;

    /**
     * @var integer
     */
    private $media_type;

    /**
     * @var string
     */
    private $artist;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var string
     */
    private $ean;

    /**
     * @var integer
     */
    private $publication_year;

    /**
     * @var string
     */
    private $publisher;

//     /**
//      * @var string
//      */
//     private $cover;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tracks;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tracks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set media_type
     *
     * @param integer $mediaType
     * @return MusicAlbum
     */
    public function setMediaType($mediaType)
    {
        $this->media_type = $mediaType;

        return $this;
    }

    /**
     * Get media_type
     *
     * @return integer
     */
    public function getMediaType()
    {
        return $this->media_type;
    }

    /**
     * Set artist
     *
     * @param string $artist
     * @return MusicAlbum
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return MusicAlbum
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set ean
     *
     * @param string $ean
     * @return MusicAlbum
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get ean
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set publication_year
     *
     * @param integer $publicationYear
     * @return MusicAlbum
     */
    public function setPublicationYear($publicationYear)
    {
        $this->publication_year = $publicationYear;

        return $this;
    }

    /**
     * Get publication_year
     *
     * @return integer
     */
    public function getPublicationYear()
    {
        return $this->publication_year;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     * @return MusicAlbum
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

//     /**
//      * Set cover
//      *
//      * @param string $cover
//      * @return MusicAlbum
//      */
//     public function setCover($cover)
//     {
//         $this->cover = $cover;

//         return $this;
//     }

//     /**
//      * Get cover
//      *
//      * @return string
//      */
//     public function getCover()
//     {
//         return $this->cover;
//     }

    /**
     * Add tracks
     *
     * @param \Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks
     * @return MusicAlbum
     */
    public function addTrack(\Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks)
    {
        $this->tracks[] = $tracks;

        return $this;
    }

    /**
     * Remove tracks
     *
     * @param \Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks
     */
    public function removeTrack(\Fulgurio\MediaLibraryManagerBundle\Entity\MusicTrack $tracks)
    {
        $this->tracks->removeElement($tracks);
    }

    /**
     * Get tracks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTracks()
    {
        return $this->tracks;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if ($this->publication_year < 1900 || $this->publication_year > date('Y') + 1)
        {
            $context->addViolationAt(
                    'publication_year',
                    'fulgurio.medialibrarymanager.music.invalid_publication_year',
                    array(),
                    null
                    );
        }
    }
}
